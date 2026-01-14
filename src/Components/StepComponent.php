<?php

namespace Spatie\LivewireWizard\Components;

use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;
use Spatie\LivewireWizard\Components\Concerns\StepAware;
use Spatie\LivewireWizard\Support\ComponentHydrator;
use Spatie\LivewireWizard\Support\State;

abstract class StepComponent extends Component
{
    use StepAware;

    public ?string $wizardClassName = null;

    public array $allStepNames = [];
    public array $allStepsState = [];

    /** @var class-string<State> */
    public string $stateClassName = State::class;

    public function dispatchDehydrated($event, ...$params)
    {
        $hydrator = app(ComponentHydrator::class);
        $newParams = collect($params)->map(fn ($param) => $hydrator->dehydrateData($this, $param))->toArray();

        return parent::dispatch($event, ...$newParams);
    }

    public function previousStep()
    {
        $this->dispatchDehydrated('previousStep', $this->state()->currentStep())->to($this->wizardClassName);
    }

    public function nextStep()
    {
        $this->dispatchDehydrated('nextStep', $this->state()->currentStep())->to($this->wizardClassName);
    }

    public function showStep(string $stepName)
    {
        $this->dispatchDehydrated('showStep', toStepName: $stepName, currentStepState: $this->state()->currentStep())->to($this->wizardClassName);
    }

    public function hasPreviousStep()
    {
        return ! empty($this->allStepNames) && $this->allStepNames[0] !== $this->componentName(static::class);
    }

    public function hasNextStep()
    {
        return end($this->allStepNames) !== $this->componentName(static::class);
    }

    public function stepInfo(): array
    {
        return [];
    }

    public function state(): State
    {
        /** @var State $stateClass */
        $stateClass = new $this->stateClassName();

        $stepName = $this->componentName(static::class);

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
