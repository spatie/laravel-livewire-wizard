<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class CustomStateStepComponent extends StepComponent
{
    public string $stepPropertyName = 'stepPropertyValue';

    public function render()
    {
        return <<<'blade'
            <div>
                <div id="currentStepState">@json($this->state()->currentStep())</div>
                <div id="allStepState">@json($this->state()->all())</div>
                foo method: {{ $this->state()->foo() }}
                state get: {{ $this->state()->get('custom-state-step.stepPropertyName') }}

            </div>
        blade;
    }
}
