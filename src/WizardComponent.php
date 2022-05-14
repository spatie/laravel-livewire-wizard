<?php

namespace Spatie\LivewireWizard;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Livewire;
use Spatie\LivewireWizard\Exceptions\InvalidStepComponent;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Exceptions\NoStepsReturned;

abstract class WizardComponent extends Component
{
    public string $currentStep;
    public array $currentStepState = [];

    public array $stepState = [];

    protected $listeners = [
        'previousStep',
        'nextStep',
        'activateStep',
    ];

    /** @return <int, class-string<StepComponent> */
    abstract public function steps(): array;

    public function mount()
    {
        $this->currentStep = $this->stepNames()->first();
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
            ->before(fn (string $step) => $step === $this->currentStep);

        if (! $previousStep) {
            throw NoPreviousStep::make(self::class, $this->currentStep);
        }

        $this->activateStep($previousStep, $currentStepState);
    }

    public function nextStep(array $currentStepState)
    {
        $nextStep = collect($this->stepNames())
            ->after(fn (string $step) => $step === $this->currentStep);

        if (! $nextStep) {
            throw NoNextStep::make(self::class, $this->currentStep);
        }

        $this->activateStep($nextStep, $currentStepState);
    }

    public function activateStep($toStepName, array $currentStepState)
    {
        $this->stepState[$this->currentStep] = $currentStepState;

        $this->currentStep = $toStepName;
    }

    public function render()
    {
        $this->currentStepState = $this->stepState[$this->currentStep] ?? [];

        $this->currentStepState['wizardState'] = $this->stepState;

        return <<<'blade'
            <div>
                @livewire($activeStep, $activeStepState, key($activeStep))
            </div>
blade;
    }
}
