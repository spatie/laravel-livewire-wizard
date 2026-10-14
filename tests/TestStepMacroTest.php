<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\MountStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithMountComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithSfcSteps;

it('can test a step without state', function () {
    MyWizardComponent::testStep(FirstStepComponent::class)
        ->assertSee('first step');
});

it('can test a step with initial state', function () {
    MyWizardComponent::testStep(SecondStepComponent::class, [
        'first-step' => [
            'order' => 220,
        ],
    ])
        ->assertSee('Order value is: 220');
});

it('cannot test a step with invalid initial state', function () {
    MyWizardComponent::testStep(SecondStepComponent::class, [
        'random-fake-step' => [
            'order' => 220,
        ],
    ]);
})->throws(Exception::class);

it('can test wizard with params', function () {
    WizardWithMountComponent::testStep(MountStepComponent::class, params: [
        'counter' => 999,
    ])
        ->assertSee('Mount step');
});

it('cannot test wizard without required params', function () {
    WizardWithMountComponent::testStep(MountStepComponent::class);
})->throws(Exception::class);

it('can test a step of a compiled wizard', function () {
    Livewire::test('sfc-wizard')
        ->testStep('sfc-second-step')
        ->assertSee('sfc second step');
});

it('can test a step of a compiled wizard with initial state', function () {
    Livewire::test('sfc-wizard')
        ->testStep('sfc-second-step', [
            'sfc-first-step' => ['name' => 'Freek'],
        ])
        ->assertSee('hello Freek');
});

it('can test a compiled step of a class-based wizard', function () {
    WizardWithSfcSteps::testStep('sfc-second-step', [
        'sfc-first-step' => ['name' => 'Rias'],
    ])
        ->assertSee('hello Rias');
});
