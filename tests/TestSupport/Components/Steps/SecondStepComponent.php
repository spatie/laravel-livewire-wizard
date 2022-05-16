<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class SecondStepComponent extends StepComponent
{
    public function render()
    {
        return <<<'blade'
            <div>
            second step
            </div>
        blade;
    }
}
