<?php

use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithAuth;

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

it('handles unauthorized actions gracefully', function () {
    WizardWithAuth::testStep(FirstStepComponent::class, [
        'first-step' => [
            'order' => 220,
        ],
    ])->assertForbidden();
});
