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

    public function previousStep()
    {
        $this->emitUp('previousStep', $this->currentStepState());
    }

    public function nextStep()
    {
        $this->emitUp('nextStep', $this->currentStepState());
    }

    public function activateStep(string $stepName)
    {
        $this->emitUp('activateStep', $stepName, $this->currentStepState());
    }

    public function allStepsState(): array
    {
        $stepName = Livewire::getAlias(static::class);

        return array_merge($this->allStepsState, [$stepName => $this->currentStepState()]);
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
