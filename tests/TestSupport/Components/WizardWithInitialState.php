<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;

class WizardWithInitialState extends WizardComponent
{
    /**
     * @inheritDoc
     */
    public function steps(): array
    {
        return [
            FirstStepComponent::class,
            SecondStepComponent::class,
        ];
    }

    public function getInitialState(): array
    {
        return [
            'order' => 1,
        ];
    }
}
