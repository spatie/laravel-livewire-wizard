<?php

use Livewire\Livewire;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithCustomStateObject;
use Spatie\LivewireWizard\Tests\TestSupport\Components\WizardWithInvalidCustomStateObject;
use Spatie\LivewireWizard\Tests\TestSupport\State\CustomState;

it('can mount a wizard with a custom state class', function () {
    Livewire::test(WizardWithCustomStateObject::class)->assertSuccessful();
});

it('will throw an exception when a wizard uses an invalid custom state class', function () {
    Livewire::test(WizardWithInvalidCustomStateObject::class);
})->throws('invalid state class');

it('can use a custom state class', function () {
    $wizard = Livewire::test(WizardWithCustomStateObject::class, [
        'stateClassName' => CustomState::class,
    ]);

    $wizard
        ->assertSuccessful()
        ->assertSeeText('foo method: bar')
        ->assertSeeText('state get: stepPropertyValue');

    $currentStepState = $wizard->jsonContent('currentStepState');
    expect($currentStepState['stepPropertyName'])->toBe('stepPropertyValue');

    $allStepState = $wizard->jsonContent('allStepState');
    expect($allStepState)->toHaveKey('custom-state-step');
});
