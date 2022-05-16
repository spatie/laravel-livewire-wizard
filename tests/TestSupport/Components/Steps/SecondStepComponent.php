<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class SecondStepComponent extends StepComponent
{
    public $counter = 0;

    public function increment()
    {
        $this->counter = $this->counter + 1;
    }

    public function stepInfo(): array
    {
        return [
            'label' => 'Second step',
        ];
    }

    public function render()
    {
        return view('test::second-step', [
            'allStepState' => $this->allStepsState(),
            'firstStepState' => $this->stateForStep('second-step'),
            'currentStepState' => $this->currentStepState(),
        ]);
    }
}
