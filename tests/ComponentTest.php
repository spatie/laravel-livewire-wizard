<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\StepWithValidationComponent;

it('can render a wizard component', function () {
    Livewire::test(MyWizardComponent::class)->assertSuccessful();
});

it('can render a step component', function () {
    Livewire::test(FirstStepComponent::class)->assertSuccessful();
});

it('emits next step event', function () {
    Livewire::test(FirstStepComponent::class)
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertEmittedUp('nextStep');
});

it('can validate state before next step', function () {
    Livewire::test(StepWithValidationComponent::class)
        ->call('nextStep')
        ->assertHasErrors([
            'name' => 'required'
        ]);
});
