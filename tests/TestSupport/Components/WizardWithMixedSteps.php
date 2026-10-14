<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;

class WizardWithMixedSteps extends WizardComponent
{
    public function steps(): array
    {
        return [
            FirstStepComponent::class,
            'sfc-second-step',
        ];
    }
}
