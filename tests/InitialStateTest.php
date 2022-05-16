<?php

use Livewire\Livewire;
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

it('can handle manually passed in state', function () {
    $initialState = [
        'first-step' => [
            'order' => 123,
        ],
        'second-step' => [
            'counter' => 2,
        ],
    ];

    $this->wizard = Livewire::test(WizardWithInitialState::class, [
        'initialState' => $initialState,
        'order' => 456, // will be ignored since initialState is passed
    ])
        ->assertSuccessful()
        ->assertSet('allStepState', $initialState);
});
