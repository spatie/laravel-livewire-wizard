<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\DehydratedStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Enums\LikesCoffeeEnum;

class SecondHydratedStepComponent extends DehydratedStepComponent
{
    public int $order = 0;
    public LikesCoffeeEnum $likes_coffee = LikesCoffeeEnum::Yes;

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
