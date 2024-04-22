<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Livewire\Attributes\Session;
use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;

class WizardWithPersistState extends WizardComponent
{
    #[Session]
    public array $allStepState = [];

    public function steps(): array
    {
        return [
            FirstStepComponent::class,
            SecondStepComponent::class,
        ];
    }

    public function initialState(): ?array
    {
        return [
            'first-step' => [
                'order' => 123,
            ],
        ];
    }
}
