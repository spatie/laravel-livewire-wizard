<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithInitialState;

beforeEach(function () {
    $this->wizard = Livewire::test(WizardWithInitialState::class, [
        'order' => 123,
    ]);
});

it('can set initial state via a passed props', function () {
    $this->wizard
        ->assertSuccessful()
        ->assertSet('allStepState', [
            'first-step' => [
                'order' => 123,
            ],
        ]);
});
