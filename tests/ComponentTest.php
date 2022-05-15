<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SkipStepComponent;

it('can render a wizard component', function () {
    Livewire::test(MyWizardComponent::class)->assertSuccessful();
});

it('can render a step component', function () {
    Livewire::test(FirstStepComponent::class)->assertSuccessful();
});

it('skips current step', function () {
   Livewire::test(SkipStepComponent::class)
       ->assertEmittedUp('nextStep');
});
