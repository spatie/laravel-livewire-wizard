<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class FirstStepComponent extends StepComponent
{
    public function render()
    {
        return view('test::first-step');
    }

    public function stepInfo(): array
    {
        return [
            'label' => 'First step',
        ];
    }
}
