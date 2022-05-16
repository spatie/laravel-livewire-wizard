<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;

beforeEach(function () {
    $this->wizard = Livewire::test(MyWizardComponent::class);

    $this->firstStep = Livewire::test(FirstStepComponent::class);
});

it('can render a wizard component', function () {
    $this->wizard->assertSuccessful();
});

it('can render a step component', function () {
    $this->firstStep->assertSuccessful();
});

it('can render the next and previous step', function () {
    $this->wizard->assertSee('first step');

    $this->firstStep
        ->call('nextStep')
        ->emitEvents()->in($this->wizard);
    $this->wizard->assertSee('second step');

    Livewire::test(SecondStepComponent::class)
        ->call('previousStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee('first step');
});

it('will throw an exception when going to the previous step on the first step', function () {
    $this->firstStep
        ->call('previousStep')
        ->emitEvents()->in($this->wizard);
})->throws(NoPreviousStep::class);
