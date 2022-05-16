<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SkipStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithSkippedStepComponent;

beforeEach(function () {
    $this->wizard = Livewire::test(WizardWithSkippedStepComponent::class);
    $this->firstStep = Livewire::test(FirstStepComponent::class);
    $this->skipStep = Livewire::test(SkipStepComponent::class);
});

it('skips the step when shouldSkip returns true', function () {
    $wizard = $this->wizard->assertSee('first step');

    $this->firstStep
        ->assertSuccessful()
        ->call('nextStep')
        ->emitEvents()->in($wizard);

    $this->skipStep
        ->assertSuccessful()
        ->emitEvents()->in($wizard);

    $wizard->assertSee('third step');
});
