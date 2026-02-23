<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\DottedFirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\DottedSecondStepComponent;

class WizardWithDottedStepNames extends WizardComponent
{
    public function steps(): array
    {
        return [
            DottedFirstStepComponent::class,
            DottedSecondStepComponent::class,
        ];
    }
}
