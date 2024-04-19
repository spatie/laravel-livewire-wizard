<?php

use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithInitialState;

beforeEach(function () {
    $this->wizard = Livewire::test(WizardWithInitialState::class, [
        'order' => 123,
    ]);
});

it('can set initial state via passed props', function () {
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

    $firstStepState = $this->wizard->getStepState(FirstStepComponent::class);

    Livewire::test(FirstStepComponent::class, $firstStepState)
        ->assertSuccessful()
        ->assertSet('order', 123);
});

it('ignores initial state if property does not exist', function () {
    $initialState = [
        'first-step' => [
            'name' => 'Freek',
        ],
    ];

    $this->wizard = Livewire::test(WizardWithInitialState::class, [
        'initialState' => $initialState,
        'order' => 456, // will be ignored since initialState is passed
    ])
        ->assertSuccessful();

    $firstStepState = $this->wizard->getStepState(FirstStepComponent::class);

    Livewire::test(FirstStepComponent::class, $firstStepState)
        ->assertSuccessful()
        ->assertNotSet('name', 'Freek');
});

it('state is not persisted when wizard recreated', function () {
    $this->wizard->call('setStepState', 'first-step', ['order' => 456]);

    $wizard = Testable::create(WizardWithInitialState::class, [
        'order' => 123,
    ]);

    Livewire::test(FirstStepComponent::class, $wizard->getStepState(FirstStepComponent::class))
        ->assertSet('order', 123);
});
