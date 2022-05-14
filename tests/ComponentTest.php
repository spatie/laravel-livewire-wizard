<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;

it('can render a wizard component', function() {
   Livewire::test(MyWizardComponent::class)->assertSuccessful();
});
