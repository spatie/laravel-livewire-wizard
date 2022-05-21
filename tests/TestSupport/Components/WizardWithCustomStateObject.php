<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\CustomStateStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\State\CustomState;

class WizardWithCustomStateObject extends WizardComponent
{
    public function steps(): array
    {
        return [
            CustomStateStepComponent::class,
            SecondStepComponent::class,
        ];
    }

    public function stateClass(): string
    {
        return CustomState::class;
    }
}
