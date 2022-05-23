<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SkipStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithSkippedStepBasedOnState;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithSkippedStepComponent;

beforeEach(function () {
    $this->wizard = Livewire::test(WizardWithSkippedStepComponent::class);
    $this->firstStep = Livewire::test(FirstStepComponent::class);
    $this->skipStep = Livewire::test(SkipStepComponent::class);

    $this->wizardWithState = Livewire::test(WizardWithSkippedStepBasedOnState::class);
});

it('skips the step when shouldSkip returns true', function () {
    $wizard = $this->wizard->assertSee('first step');

    $this->firstStep
        ->assertSuccessful()
        ->call('nextStep')
        ->emitEvents()->in($wizard);

    $wizard->assertSee('third step');
});

it('skips step based on state', function () {
    $wizard = $this->wizardWithState->assertSee('first step');

    $wizard->call('setStepState', 'skip-step-state', [
        'shouldSkipDecider' => true,
    ]);

    $this->firstStep
        ->assertSuccessful()
        ->call('nextStep')
        ->emitEvents()->in($wizard);

    $wizard->assertSee('third step');
});
