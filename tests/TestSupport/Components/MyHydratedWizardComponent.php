<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\HydratedWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstHydratedStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondHydratedStepComponent;

class MyHydratedWizardComponent extends HydratedWizardComponent
{
    public function steps(): array
    {
        return [
            FirstHydratedStepComponent::class,
            SecondHydratedStepComponent::class,
        ];
    }
}
