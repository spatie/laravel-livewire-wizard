<?php

namespace Spatie\LivewireWizard\Components;

use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Drawer\Utils;
use Livewire\Mechanisms\ComponentRegistry;
use Spatie\LivewireWizard\Components\Concerns\MountsWizard;
use Spatie\LivewireWizard\Exceptions\InvalidStepComponent;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Exceptions\NoStepsReturned;
use Spatie\LivewireWizard\Exceptions\StepDoesNotExist;
use Spatie\LivewireWizard\Support\ComponentHydrator;
use Spatie\LivewireWizard\Support\State;

abstract class WizardComponent extends Component
{
    use MountsWizard;

    public array $allStepState = [];
    public ?string $currentStepName = null;

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
                $alias = app(ComponentRegistry::class)->getName($stepClassName);

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

    #[On('previousStep')]
    public function previousStep(array $currentStepState)
    {
        $previousStep = collect($this->stepNames())
            ->before(fn(string $step) => $step === $this->currentStepName);

        if (! $previousStep) {
            throw NoPreviousStep::make(self::class, $this->currentStepName);
        }

        $this->showStep($previousStep, $currentStepState);
    }

    #[On('nextStep')]
    public function nextStep(array $currentStepState)
    {
        $nextStep = collect($this->stepNames())
            ->after(fn(string $step) => $step === $this->currentStepName);

        if (! $nextStep) {
            throw NoNextStep::make(self::class, $this->currentStepName);
        }

        $this->showStep($nextStep, $currentStepState);
    }

    #[On('showStep')]
    public function showStep($toStepName, array $currentStepState = [])
    {
        if ($this->currentStepName) {
            $state = $currentStepState;
            if (Utils::isSyntheticTuple($state)) {
                $state = app(ComponentHydrator::class)->hydrateData($this, $currentStepState);
            }

            $this->setStepState($this->currentStepName, $state);
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

    public function getCurrentStepState(?string $step = null): array
    {
        $stepName = $step ?? $this->currentStepName;

        $stepName = class_exists($stepName)
            ? app(ComponentRegistry::class)->getName($stepName)
            : $stepName;

        throw_if(
            ! $this->stepNames()->contains($stepName),
            StepDoesNotExist::stepNotFound($stepName)
        );

        return array_merge(
            $this->allStepState[$stepName] ?? [],
            [
                'allStepNames' => $this->stepNames()->toArray(),
                'allStepsState' => $this->allStepState,
                'stateClassName' => $this->stateClass(),
                'wizardClassName' => static::class,
            ],
        );
    }

    public function render()
    {
        $currentStepState = $this->getCurrentStepState();

        return view('livewire-wizard::wizard', compact('currentStepState'));
    }

    /** @return class-string<State> */
    public function stateClass(): string
    {
        return State::class;
    }
}
