<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FourthStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\MountStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;

class WizardWithMountComponent extends WizardComponent
{
    public int $counter;

    public function mount(int $counter): void
    {
        $this->counter = $counter;
    }

    public function steps(): array
    {
        return [
            MountStepComponent::class,
        ];
    }
}
