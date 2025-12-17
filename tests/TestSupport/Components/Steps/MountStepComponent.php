<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class MountStepComponent extends StepComponent
{
    public function render()
    {
        return view('test::mount-step');
    }
}
