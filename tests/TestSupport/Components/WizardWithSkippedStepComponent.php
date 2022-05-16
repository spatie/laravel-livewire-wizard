<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SkipStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;

class WizardWithSkippedStepComponent extends \Spatie\LivewireWizard\Components\WizardComponent
{
    /**
     * @inheritDoc
     */
    public function steps(): array
    {
        return [
            FirstStepComponent::class,
            SkipStepComponent::class,
            ThirdStepComponent::class,
        ];
    }
}
