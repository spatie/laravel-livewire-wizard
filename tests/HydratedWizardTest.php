<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Components\HydratedWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstHydratedStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyHydratedWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondHydratedStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Enums\LikesCoffeeEnum;

use function Spatie\Snapshots\assertMatchesHtmlSnapshot;

beforeEach(function () {
    $this->wizard = Livewire::test(MyHydratedWizardComponent::class);
    $this->firstStep = Livewire::test(FirstHydratedStepComponent::class);
});

it('can render the wizard component', function () {
    $this->wizard->assertSuccessful();
});

it('can render a step component', function () {
    $this->firstStep
        ->assertSuccessful()
        ->assertSee('first step');
});

it('can remember state for forms', function () {
    $this->wizard->assertSee('first step');

    $this->firstStep
        ->set([
            'userForm' => [
                'name' => 'John Doe',
                'email' => 'john@doe.nl',
                'likes_coffee' => LikesCoffeeEnum::No->value,
            ]
        ])
        ->assertSee('John Doe')
        ->assertSee('john@doe.nl')
        ->assertSee('no')
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($this->wizard);
    $this->wizard->assertSee('second step');

    Livewire::test(SecondHydratedStepComponent::class)
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($this->wizard);

    $this->wizard
        ->assertSee('first step')
        ->assertSee('John Doe')
        ->assertSee('john@doe.nl')
        ->assertSee('no');
});

it('can remember enums in steps', function () {
    $wizard = Livewire::test(MyHydratedWizardComponent::class, ['showStep' => 'dehydrated-second-step']);
    $wizard->assertSee('second step')
        ->assertSee('yes');

    Livewire::test(SecondHydratedStepComponent::class)
        ->update([], [
            'order' => 2,
            'likes_coffee' => LikesCoffeeEnum::Alittle->value
        ])
        ->call('previousStep')
        ->assertDispatched('previousStep')
        ->emitEvents()->in($wizard);

    $wizard->assertSee('first step');
    $this->firstStep
        ->call('nextStep')
        ->assertDispatched('nextStep')
        ->emitEvents()->in($wizard);

    $wizard
        ->assertSee('second step')
        ->assertSee(2)
        ->assertSee('alittle');
});
