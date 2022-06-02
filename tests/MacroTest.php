<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Exceptions\StepDoesNotExist;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;

beforeEach(function () {
    $this->wizard = Livewire::test(MyWizardComponent::class);
});

it('throws an exception when state cannot be found', function () {
    $this->wizard->getStepState('three-thousand');
})->throws(StepDoesNotExist::class, 'Step `three-thousand` does not exist.');
