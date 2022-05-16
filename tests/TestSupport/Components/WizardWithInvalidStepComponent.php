<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\InvalidStepComponent;

class WizardWithInvalidStepComponent extends WizardComponent
{
    public function steps(): array
    {
        return [
            InvalidStepComponent::class,
        ];
    }
}
