<?php

use Livewire\Livewire;
use Livewire\Mechanisms\ComponentRegistry;
use Spatie\CollectionMacros\Macros\Fourth;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Exceptions\StepDoesNotExist;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FourthStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithInvalidStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Enums\LikesCoffeeEnum;

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
        ->assertDispatched('nextStep')
        ->emitEvents()->in($this->wizard);
    $this->wizard->assertSee('second step');

    Livewire::test(SecondStepComponent::class)
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee('first step');
});

it('can go to a specific step', function () {
    $this->firstStep
        ->call('showStep', 'third-step')
        ->assertDispatched('showStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee('third step');
});

it('throws an exception when going to the previous step on the first step', function () {
    $this->firstStep
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($this->wizard);
})->throws(NoPreviousStep::class);

it('throws an exception when going to the next step on the last step', function () {
    $wizard = Livewire::test(MyWizardComponent::class, ['showStep' => 'fourth-step']);

    Livewire::test(FourthStepComponent::class)
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($wizard);
})->throws(NoNextStep::class);

it('will throw an exception if the wizard contains an invalid step', function () {
    Livewire::test(WizardWithInvalidStepComponent::class);
})->throws('did return an invalid step component');

it('will save and restore state when switching steps', function () {
    $this->firstStep
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee(['second step', 'counter: 0']);

    Livewire::test(SecondStepComponent::class)
        ->call('increment')
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($this->wizard);

    $this->firstStep
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee(['second step', 'counter: 1']);
});

it('cannot set state if step does not exist', function () {
    $this->wizard
        ->call('setStepState', 'fake-step', []);
})->throws(StepDoesNotExist::class);

it('has a couple of handy methods to get state', function () {
    $this->firstStep
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard->assertSee(['second step', 'counter: 0']);

    Livewire::test(SecondStepComponent::class)
        ->call('increment')
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($this->wizard);

    $this->firstStep
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($this->wizard);

    $allStepState = $this->wizard->jsonContent('allStepState');
    expect($allStepState['second-step']['allStepNames'])->toBe([
        'first-step',
        'second-step',
        'third-step',
        'fourth-step',
    ]);

    $currentStepState = $this->wizard->jsonContent('currentStepState');
    expect($currentStepState['counter'])->toBe(1);
});

it('has a steps property to render navigation', function () {
    $this->firstStep
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($this->wizard);

    $this->firstStep
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($this->wizard);

    Livewire::test(SecondStepComponent::class)
        ->call('increment')
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($this->wizard);

    $navigationHtml = $this->wizard->htmlContent('navigation');

    assertMatchesHtmlSnapshot($navigationHtml);
});

it('has the correct has step states', function () {
    // Set up the step names array to match the expected steps
    $stepNames = [
        app(ComponentRegistry::class)->getName(FirstStepComponent::class),
        app(ComponentRegistry::class)->getName(SecondStepComponent::class),
        app(ComponentRegistry::class)->getName(ThirdStepComponent::class),
    ];

    // Create instances of each step component and set the allStepNames property on them
    $this->firstStep = new FirstStepComponent();
    $this->firstStep->allStepNames = $stepNames;

    $this->secondStep = new SecondStepComponent();
    $this->secondStep->allStepNames = $stepNames;

    $this->thirdStep = new ThirdStepComponent();
    $this->thirdStep->allStepNames = $stepNames;

    expect($this->firstStep->hasPreviousStep())->toBeFalse();
    expect($this->firstStep->hasNextStep())->toBeTrue();

    expect($this->secondStep->hasPreviousStep())->toBeTrue();
    expect($this->secondStep->hasNextStep())->toBeTrue();

    expect($this->thirdStep->hasPreviousStep())->toBeTrue();
    expect($this->thirdStep->hasNextStep())->toBeFalse();
});


it('can remember state for forms', function () {
    $wizard = Livewire::test(MyWizardComponent::class, ['showStep' => 'fourth-step']);

    Livewire::test(FourthStepComponent::class)
        ->update([], [
            'form' => [
                'name' => 'John Doe',
                'email' => 'john@doe.nl',
                'likes_coffee' => LikesCoffeeEnum::No->value,
            ],
        ])
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($wizard);

    $wizard->assertSee('third step');

    Livewire::test(ThirdStepComponent::class)
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($wizard);

    $wizard
        ->assertSee('fourth step')
        ->assertSee('John Doe')
        ->assertSee('john@doe.nl')
        ->assertSee(LikesCoffeeEnum::No->value);
});

it('can remember enums in steps', function () {
    $wizard = Livewire::test(MyWizardComponent::class, ['showStep' => 'fourth-step']);

    Livewire::test(FourthStepComponent::class)
        ->update([], [
            'likesCoffee' => LikesCoffeeEnum::Alittle->value
        ])
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($wizard);

    $wizard->assertSee('third step');

    Livewire::test(ThirdStepComponent::class)
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($wizard);

    $wizard
        ->assertSee(LikesCoffeeEnum::Alittle->value);
});
