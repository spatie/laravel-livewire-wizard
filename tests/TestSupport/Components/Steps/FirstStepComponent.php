<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\StepComponent;

class FirstStepComponent extends StepComponent
{
    public function render()
    {
        return view('test::first-step');
    }
}
