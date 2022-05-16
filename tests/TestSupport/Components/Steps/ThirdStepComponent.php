<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class ThirdStepComponent extends StepComponent
{
    public function render()
    {
        return <<<'blade'
            <div>
            third step
            </div>
        blade;
    }
}
