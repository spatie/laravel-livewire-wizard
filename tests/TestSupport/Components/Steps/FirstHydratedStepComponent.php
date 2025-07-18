<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\DehydratedStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Forms\UserDataForm;

class FirstHydratedStepComponent extends DehydratedStepComponent
{
    public UserDataForm $userForm;

    public function render()
    {
        return view('test::hydrated.first-step');
    }

    public function stepInfo(): array
    {
        return [
            'label' => 'First step',
        ];
    }
}
