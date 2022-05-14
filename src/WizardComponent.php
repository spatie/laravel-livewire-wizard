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
    public string $activeStep;
    public array $activeStepState;

    public array $stepState = [];

    protected $listeners = [
        'previousStep',
        'nextStep',
        'activateStep',
    ];

    /** @return <int, class-string<StepComponent> */
    abstract public function steps(): array;

    public function stepNames(): Collection
    {
        $steps = collect($this->steps())
            ->each(function(string $stepClassName) {
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

    public function mount()
    {
        $this->activeStep = $this->stepNames()->first();
    }

    public function previousStep(array $stepState)
    {
        $this->stepState[$this->activeStep] = $stepState;

        $previousStep = collect($this->stepNames())
            ->before(fn(string $step) => $step === $this->activeStep);

        if (! $previousStep) {
            throw NoPreviousStep::make(self::class, $this->activeStep);
        }

        $this->activeStep = $previousStep;
    }

    public function nextStep(array $stepState)
    {
        $this->stepState[$this->activeStep] = $stepState;

        $nextStep = collect($this->stepNames())
            ->after(fn(string $step) => $step === $this->activeStep);

        if (! $nextStep) {
            throw NoNextStep::make(self::class, $this->activeStep);
        }

        $this->activeStep = $nextStep;
    }

    public function activateStep($parameters)
    {
        dump($parameters);
        //to implement

        //throw StepDoesNotExist::make(self::class);
    }

    public function render()
    {
        $this->activeStepState = $this->stepState[$this->activeStep] ?? [];

        $this->activeStepState['wizardState'] = $this->stepState;

        return <<<'blade'
            <div>
                @livewire($activeStep, $activeStepState, key($activeStep))
            </div>
blade;
    }
}
