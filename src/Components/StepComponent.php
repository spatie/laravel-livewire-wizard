<?php

namespace Spatie\LivewireWizard\Components;

use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;
use Spatie\LivewireWizard\Components\Concerns\StepAware;
use Spatie\LivewireWizard\Support\State;

abstract class StepComponent extends Component
{
    use StepAware;

    public string $wizardComponentName;

    public array $allStepNames = [];
    public array $allStepsState = [];

    /** @var class-string<State> */
    public string $stateClassName = State::class;

    public function previousStep()
    {
        $this->dispatch('previousStep', $this->state()->currentStep())->to($this->wizardComponentName);
    }

    public function nextStep()
    {
        $this->dispatch('nextStep', $this->state()->currentStep())->to($this->wizardComponentName);
    }

    public function showStep(string $stepName)
    {
        $this->dispatch('showStep', toStepName: $stepName, currentStepState: $this->state()->currentStep())->to($this->wizardComponentName);
    }

    public function hasPreviousStep()
    {
        return ! empty($this->allStepNames) && $this->allStepNames[0] !== app(ComponentRegistry::class)->getName(static::class);
    }

    public function hasNextStep()
    {
        return end($this->allStepNames) !== app(ComponentRegistry::class)->getName(static::class);
    }

    public function stepInfo(): array
    {
        return [];
    }

    public function state(): State
    {
        /** @var State $stateClass */
        $stateClass = new $this->stateClassName();

        $stepName = app(ComponentRegistry::class)->getName(static::class);

        $allState = array_merge(
            $this->allStepsState ?? [],
            [$stepName => $this->all()]
        );

        $stateClass
            ->setAllState($allState)
            ->setCurrentStepName($stepName);

        return $stateClass;
    }
}
