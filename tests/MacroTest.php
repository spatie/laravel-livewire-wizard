<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Exceptions\StepDoesNotExist;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;

beforeEach(function () {
    $this->wizard = Livewire::test(MyWizardComponent::class);
});

it('throws an exception when state cannot be found', function () {
    $this->wizard->getStepState('three-thousand');
})->throws(StepDoesNotExist::class, 'Step `three-thousand` does not exist.');

it('gets state when no initial state is available', function () {
    expect($this->wizard->getStepState(SecondStepComponent::class))
        ->not
        ->toThrow(StepDoesNotExist::class);
});
