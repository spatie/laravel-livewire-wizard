<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Enums\LikesCoffeeEnum;
use Spatie\LivewireWizard\Tests\TestSupport\Forms\UserDataForm;

class FourthStepComponent extends StepComponent
{

    public UserDataForm $form;

    public ?LikesCoffeeEnum $likesCoffee = null;

    public function stepInfo(): array
    {
        return [
            'label' => 'Forth step',
        ];
    }

    public function render()
    {
        return view('test::fourth-step');
    }
}
