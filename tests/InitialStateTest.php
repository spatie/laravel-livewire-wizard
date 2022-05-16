<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithInitialState;

beforeEach(function () {
    $this->wizard = Livewire::test(WizardWithInitialState::class);
});

it('loads initial state', function () {
    $this->wizard
        ->assertSuccessful()
        ->assertSet('allStepState', [
            'first-step' => [
                'order' => 1,
            ],
        ]);
});
