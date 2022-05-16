<?php

namespace Spatie\LivewireWizard\Components;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Livewire;
use Spatie\LivewireWizard\Components\Concerns\MountsWizard;
use Spatie\LivewireWizard\Exceptions\InvalidStepComponent;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Exceptions\NoStepsReturned;
use Spatie\LivewireWizard\Exceptions\StepDoesNotExist;

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
            ->before(fn (string $step) => $step === $this->currentStepName);

        if (! $previousStep) {
            throw NoPreviousStep::make(self::class, $this->currentStepName);
        }

        $this->showStep($previousStep, $currentStepState);
    }

    public function nextStep(array $currentStepState)
    {
        $nextStep = collect($this->stepNames())
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

    public function render()
    {
        $currentStepState = array_merge(
            $this->allStepState[$this->currentStepName] ?? [],
            [
                'allStepNames' => $this->stepNames()->toArray(),
                'allStepsState' => $this->allStepState,
            ],
        );

        return view('livewire-wizard::wizard', compact('currentStepState'));
    }
}
