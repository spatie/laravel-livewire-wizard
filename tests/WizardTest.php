<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithInvalidStepComponent;
use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

beforeEach(function () {
    $this->wizard = Livewire::test(MyWizardComponent::class);

    $this->firstStep = Livewire::test(FirstStepComponent::class);
});

$it = it('can render a wizard component', function () {
    $this->wizard->assertSuccessful();
});

it('can render a step component', function () {
    $this->firstStep->assertSuccessful();
});

it('can show a specific step', function () {
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

it('can go to a specific step', function () {
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

it('will throw an exception if the wizard contains an invalid step', function () {
    Livewire::test(WizardWithInvalidStepComponent::class);
})->throws('did return an invalid step component');

it('will save and restore state when switching steps', function () {
    $this->firstStep
        ->call('nextStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee(['second step', 'counter: 0']);

    Livewire::test(SecondStepComponent::class)
        ->call('increment')
        ->call('previousStep')
        ->emitEvents()->in($this->wizard);

    $this->firstStep
        ->call('nextStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee(['second step', 'counter: 1']);
});

it('has a couple of handy methods to get state', function () {
    $this->firstStep
        ->call('nextStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee(['second step', 'counter: 0']);

    Livewire::test(SecondStepComponent::class)
        ->call('increment')
        ->call('previousStep')
        ->emitEvents()->in($this->wizard);

    $this->firstStep
        ->call('nextStep')
        ->emitEvents()->in($this->wizard);

    $allStepState = $this->wizard->jsonContent('allStepState');
    expect($allStepState['second-step']['allStepNames'])->toBe([
        'first-step',
        'second-step',
        'third-step',
    ]);

    $currentStepState = $this->wizard->jsonContent('currentStepState');
    expect($currentStepState['counter'])->toBe(1);
});

it('has a steps property to render navigation', function () {
    $this->firstStep
        ->call('nextStep')
        ->emitEvents()->in($this->wizard);

    $this->firstStep
        ->call('nextStep')
        ->emitEvents()->in($this->wizard);

    Livewire::test(SecondStepComponent::class)
        ->call('increment')
        ->call('previousStep')
        ->emitEvents()->in($this->wizard);

    $navigationHtml = $this->wizard->htmlContent('navigation');

    assertMatchesHtmlSnapshot($navigationHtml);
});
