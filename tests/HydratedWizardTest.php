<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstHydratedStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyHydratedWizardComponent;

use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

beforeEach(function () {
    $this->wizard = Livewire::test(MyHydratedWizardComponent::class);

    $this->firstStep = Livewire::test(FirstHydratedStepComponent::class);
});

it('can render the wizard component', function () {
    $this->wizard->assertSuccessful();
});

it('can render a step component', function () {
    $this->firstStep->assertSuccessful();
});
