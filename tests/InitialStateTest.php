<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithInitialState;

beforeEach(function () {
    $this->wizard = Livewire::test(WizardWithInitialState::class);
});

it('loads initial state', function () {
    $this->wizard
        ->assertSuccessful()
        ->assertSet('allStepState', [
            Livewire::getAlias(FirstStepComponent::class) => [
                'order' => 1,
            ],
        ]);
});
