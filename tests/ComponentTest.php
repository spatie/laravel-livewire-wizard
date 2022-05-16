<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;

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

it('can show a specific step', function() {
    Livewire::test(MyWizardComponent::class, ['showStep' => 'third-step'])
        ->assertSuccessful()
        ->assertSee('third step');
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

it('can go to a specific step', function() {
    $this->firstStep
        ->call('showStep', 'third-step')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee('third step');
});

it('throws an exception when going to the previous step on the first step', function () {
    $this->firstStep
        ->call('previousStep')
        ->emitEvents()->in($this->wizard);
})->throws(NoPreviousStep::class);

it('throws an exception when going to the next step on the last step', function () {
    $wizard = Livewire::test(MyWizardComponent::class, ['showStep' => 'third-step']);

    Livewire::test(ThirdStepComponent::class)
        ->call('nextStep')
        ->emitEvents()->in($wizard);
})->throws(NoNextStep::class);
