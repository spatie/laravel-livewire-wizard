<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;

it('can render a wizard component', function () {
    Livewire::test(MyWizardComponent::class)->assertSuccessful();
});

it('can render a step component', function() {
    Livewire::test(FirstStepComponent::class)->assertSuccessful();
});
