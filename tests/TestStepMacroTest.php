<?php

use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\MountStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithMountComponent;

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

it('cant test wizard without params', function () {
    WizardWithMountComponent::testStep(MountStepComponent::class);
})->throws(Exception::class);
