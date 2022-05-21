<?php

namespace Spatie\LivewireWizard\Components;

use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\Livewire;
use Spatie\LivewireWizard\Components\Concerns\StepAware;
use Spatie\LivewireWizard\Support\State;

abstract class StepComponent extends Component
{
    use StepAware;

    public array $allStepNames = [];
    public array $steps = [];
    public array $allStepsState = [];

    /** @var class-string<State> */
    public string $stateClassName = State::class;

    public function previousStep()
    {
        $this->emitUp('previousStep', $this->state()->currentStep());
    }

    public function nextStep()
    {
        $this->emitUp('nextStep', $this->state()->currentStep());
    }

    public function showStep(string $stepName)
    {
        $this->emitUp('showStep', $stepName, $this->state()->currentStep());
    }

    /*
    public function allStepsState(string $key = null)
    {
        $stepName = Livewire::getAlias(static::class);

        $state = array_merge(
            $this->allStepsState ?? [],
            [$stepName => $this->currentStepState()]
        );

        if ($key) {
            return Arr::get($state, $key);
        }

        return $state;
    }

    public function stateForStep(string $stepName): array
    {
        $state = $this->allStepsState()[$stepName] ?? [];

        return Arr::except($state, 'allStepsState');
    }

    protected function currentStepState(): array
    {
        return Arr::except($this->all(), 'allStepsState');
    }
    */

    public function stepInfo(): array
    {
        return [];
    }

    public function state(): State
    {
        /** @var State $stateClass */
        $stateClass = new $this->stateClassName();

        $stepName = Livewire::getAlias(static::class);

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
