<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;

class WizardWithInitialState extends WizardComponent
{
    public function mount(string $order)
    {
        $this->order = $order;
    }

    public function steps(): array
    {
        return [
            FirstStepComponent::class,
            SecondStepComponent::class,
        ];
    }

    public function initialState(): array
    {
        return [
            'first-step' => [
                'order' => $this->order,
            ],
        ];
    }
}
