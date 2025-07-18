<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\DehydratedStepComponent;

class SecondHydratedStepComponent extends DehydratedStepComponent
{
    public int $order = 0;

    public function render()
    {
        return view('test::hydrated.second-step');
    }

    public function stepInfo(): array
    {
        return [
            'label' => 'Second step',
        ];
    }
}
