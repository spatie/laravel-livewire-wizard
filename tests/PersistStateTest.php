<?php

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithPersistState;

it('can restore the persisted state', function () {
    $wizard = Testable::create(WizardWithPersistState::class, [
        'initialState' => [
            'first-step' => [
                'order' => 123
            ]
        ]
    ]);

    $wizard->call('setStepState', 'first-step', ['order' => 456]);

    Livewire::test(FirstStepComponent::class, $wizard->getStepState(FirstStepComponent::class))
        ->assertSet('order', 456);

    // recreate the wizard
    $wizard = Testable::create(WizardWithPersistState::class, [
        'initialState' => [
            'first-step' => [
                'order' => 123
            ]
        ]
    ]);

    Livewire::test(FirstStepComponent::class, $wizard->getStepState(FirstStepComponent::class))
        ->assertSet('order', 456);
});
