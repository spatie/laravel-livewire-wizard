<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SkipStepBasedOnStateComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;

class WizardWithSkippedStepBasedOnState extends WizardComponent
{
    public function steps(): array
    {
        return [
            FirstStepComponent::class,
            SkipStepBasedOnStateComponent::class,
            ThirdStepComponent::class,
        ];
    }
}
