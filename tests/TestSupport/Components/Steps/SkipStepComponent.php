<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class SkipStepComponent extends StepComponent
{
    public function shouldSkip()
    {
        return true;
    }

    public function render()
    {
        return <<<'blade'
            <div>
            Skip this step.
            </div>
        blade;
    }
}
