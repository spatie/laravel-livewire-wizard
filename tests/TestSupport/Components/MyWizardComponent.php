<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;
use Spatie\LivewireWizard\WizardComponent;

class MyWizardComponent extends WizardComponent
{
    public function steps(): array
    {
        return [
            FirstStepComponent::class,
            SecondStepComponent::class,
            ThirdStepComponent::class,
        ];
    }
}
