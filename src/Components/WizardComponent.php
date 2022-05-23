<?php

namespace Spatie\LivewireWizard\Components;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\LifecycleManager;
use Livewire\Livewire;
use Spatie\LivewireWizard\Components\Concerns\MountsWizard;
use Spatie\LivewireWizard\Exceptions\InvalidStepComponent;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Exceptions\NoStepsReturned;
use Spatie\LivewireWizard\Exceptions\StepDoesNotExist;
use Spatie\LivewireWizard\Support\State;

abstract class WizardComponent extends Component
{
    use MountsWizard;

    public array $allStepState = [];
    public ?string $currentStepName = null;

    protected $listeners = [
        'previousStep',
        'nextStep',
        'showStep',
    ];

    /** @return <int, class-string<StepComponent> */
    abstract public function steps(): array;

    public function initialState(): ?array
    {
        return null;
    }

    public function stepNames(): Collection
    {
        $steps = collect($this->steps())
            ->each(function (string $stepClassName) {
                if (! is_a($stepClassName, StepComponent::class, true)) {
                    throw InvalidStepComponent::doesNotExtendStepComponent(static::class, $stepClassName);
                }
            })
            ->map(function (string $stepClassName) {
                $alias = Livewire::getAlias($stepClassName);

                if (is_null($alias)) {
                    throw InvalidStepComponent::notRegisteredWithLivewire(static::class, $stepClassName);
                }

                return $alias;
            });

        if ($steps->isEmpty()) {
            throw NoStepsReturned::make(static::class);
        }

        return $steps;
    }

    public function previousStep(array $currentStepState)
    {
        $previousStep = collect($this->stepNames())
            ->reject(fn (string $step) => $this->getSkippedSteps()->contains($step))
            ->before(fn (string $step) => $step === $this->currentStepName);

        if (! $previousStep) {
            throw NoPreviousStep::make(self::class, $this->currentStepName);
        }

        $this->showStep($previousStep, $currentStepState);
    }

    public function nextStep(array $currentStepState)
    {
        $nextStep = collect($this->stepNames())
            ->reject(fn (string $step) => $this->getSkippedSteps()->contains($step))
            ->after(fn (string $step) => $step === $this->currentStepName);

        if (! $nextStep) {
            throw NoNextStep::make(self::class, $this->currentStepName);
        }

        $this->showStep($nextStep, $currentStepState);
    }

    public function showStep($toStepName, array $currentStepState = [])
    {
        if ($this->currentStepName) {
            $this->setStepState($this->currentStepName, $currentStepState);
        }

        $this->currentStepName = $toStepName;
    }

    public function setStepState(string $step, array $state = []): void
    {
        if (! $this->stepNames()->contains($step)) {
            throw StepDoesNotExist::doesNotHaveState($step);
        }

        $this->allStepState[$step] = $state;
    }

    protected function getSkippedSteps(): Collection
    {
        return $this->stepNames()
            ->map(function ($step) {
                $instance = $this->getLivewireInstance($step);

                if (! method_exists($instance, 'shouldSkip')) {
                    return false;
                }

                if (! $instance->shouldSkip()) {
                    return false;
                }

                return $step;
            })
            ->reject(fn ($value) => $value === false);
    }

    protected function getLivewireInstance(string $step)
    {
        $class = Livewire::getClass($step);

        $id = str()->random(20);

        if (class_exists($step)) {
            $step = $step::getName();
        }

        $mounted = LifecycleManager::fromInitialRequest($step, $id)
            ->boot()
            ->initialHydrate()
            ->mount($this->getStepState($step));

        return $mounted->instance;
    }

    public function render()
    {
        $currentStepState = $this->getStepState($this->currentStepName);

        return view('livewire-wizard::wizard', compact('currentStepState'));
    }

    /** @return class-string<State> */
    public function stateClass(): string
    {
        return State::class;
    }

    private function getStepState(string $step): array
    {
        return array_merge(
            $this->allStepState[$step] ?? [],
            [
                'allStepNames' => $this->stepNames()->toArray(),
                'allStepsState' => $this->allStepState,
                'stateClassName' => $this->stateClass(),
            ],
        );
    }
}
