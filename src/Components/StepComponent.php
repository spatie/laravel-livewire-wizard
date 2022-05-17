<?php

namespace Spatie\LivewireWizard\Components;

use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\Livewire;
use Spatie\LivewireWizard\Components\Concerns\StepAware;

abstract class StepComponent extends Component
{
    use StepAware;

    public array $allStepNames = [];
    public array $steps = [];
    public array $allStepsState = [];

    public function previousStep()
    {
        $this->emitUp('previousStep', $this->currentStepState());
    }

    public function nextStep()
    {
        $this->emitUp('nextStep', $this->currentStepState());
    }

    public function showStep(string $stepName)
    {
        $this->emitUp('showStep', $stepName, $this->currentStepState());
    }

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

    public function stepInfo(): array
    {
        return [];
    }
}
