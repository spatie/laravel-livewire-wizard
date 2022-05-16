<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class ThirdStepComponent extends StepComponent
{
    public function stepInfo(): array
    {
        return [
            'label' => 'Third step',
        ];
    }

    public function render()
    {
        return view('test::third-step');
    }
}
