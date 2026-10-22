<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;
use Livewire\Mechanisms\HandleComponents\CorruptComponentPayloadException;
use Spatie\LivewireWizard\Support\ComponentHydrator;
use Spatie\LivewireWizard\Tests\TestSupport\Components\MyWizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\HydrationProbe;

it('rejects unissued wireables in action and event parameters before reconstructing them', function (string $transport, string $method) {
    $wizard = Livewire::test(MyWizardComponent::class, ['showStep' => 'second-step']);
    HydrationProbe::$hydrations = 0;
    $state = [['probe' => [['value' => 'caller-selected'], ['s' => 'wrbl', 'class' => HydrationProbe::class]]], ['s' => 'arr']];
    $params = $method === 'showStep' ? ['third-step', $state] : [$state];

    expect(fn () => $wizard->{$transport}($method, ...$params))
        ->toThrow(CorruptComponentPayloadException::class);

    expect(HydrationProbe::$hydrations)->toBe(0);
})->with(['call', 'dispatch'])->with(['showStep', 'nextStep', 'previousStep']);

it('rejects unissued collection metadata before invoking a constructor', function () {
    $wizard = Livewire::test(MyWizardComponent::class);
    HydrationProbe::$constructions = 0;
    $state = [['probe' => [null, ['s' => 'elcln', 'class' => HydrationProbe::class, 'modelClass' => null, 'keys' => []]]], ['s' => 'arr']];

    expect(fn () => $wizard->call('showStep', 'third-step', $state))
        ->toThrow(CorruptComponentPayloadException::class);

    expect(HydrationProbe::$constructions)->toBe(0);
});

it('rejects unissued models instead of exposing their attributes through a consuming view', function () {
    Schema::create('hydration_proof_records', function (Blueprint $table) {
        $table->id();
        $table->string('marker');
    });

    try {
        $record = HydrationProofRecord::create(['marker' => 'disposable-local-record']);
        $wizard = Livewire::test(MyWizardComponent::class);
        $state = [['probe' => [null, ['s' => 'mdl', 'class' => HydrationProofRecord::class, 'key' => $record->getKey()]]], ['s' => 'arr']];

        expect(fn () => $wizard->call('showStep', 'second-step', $state))
            ->toThrow(CorruptComponentPayloadException::class);
    } finally {
        Schema::drop('hydration_proof_records');
    }
});

it('round trips signed nested state through action and event parameters', function (string $transport, string $method) {
    $wizard = Livewire::test(MyWizardComponent::class, ['showStep' => 'second-step']);
    $state = app(ComponentHydrator::class)->dehydrateData(new SecondStepComponent(), [
        'probe' => new HydrationProbe('server-issued'),
        'nested' => collect(['numbers' => [7 => 'first', 2 => 'second'], 'label' => 'Bruxelles ☕']),
    ]);
    $state = json_decode(json_encode($state, JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR);
    $params = $method === 'showStep' ? ['third-step', $state] : [$state];

    $wizard->{$transport}($method, ...$params)->assertSuccessful();

    $saved = $wizard->get('allStepState')['second-step'];

    expect($saved['probe'])->toBeInstanceOf(HydrationProbe::class)
        ->and($saved['probe']->value)->toBe('server-issued')
        ->and($saved['nested']->all())->toBe(['numbers' => [7 => 'first', 2 => 'second'], 'label' => 'Bruxelles ☕']);
})->with(['call', 'dispatch'])->with(['showStep', 'nextStep', 'previousStep']);

it('rejects altered signed state before reconstructing any objects', function (string $tampering) {
    $wizard = Livewire::test(MyWizardComponent::class);
    $state = app(ComponentHydrator::class)->dehydrateData(new SecondStepComponent(), [
        'probe' => new HydrationProbe('server-issued'),
    ]);

    match ($tampering) {
        'value' => $state[0] = str_replace('server-issued', 'caller-selected', $state[0]),
        'class' => $state[0] = str_replace('HydrationProbe', 'AnotherProbe', $state[0]),
        'synth' => $state[0] = str_replace('wrbl', 'obj', $state[0]),
        'wrapper' => $state[1]['s'] = 'arr',
        'signature' => $state[1]['checksum'] = str_repeat('0', 64),
        'missing signature' => $state[1] = ['s' => 'wizard'],
        'signature type' => $state[1]['checksum'] = [],
        'payload type' => $state[0] = [],
    };

    HydrationProbe::$hydrations = 0;
    HydrationProbe::$constructions = 0;

    expect(fn () => $wizard->dispatch('showStep', 'third-step', $state))
        ->toThrow(CorruptComponentPayloadException::class);

    expect(HydrationProbe::$hydrations)->toBe(0)
        ->and(HydrationProbe::$constructions)->toBe(0);
})->with(['value', 'class', 'synth', 'wrapper', 'signature', 'missing signature', 'signature type', 'payload type']);

it('continues accepting plain array state without interpreting nested tuples', function (string $transport) {
    $wizard = Livewire::test(MyWizardComponent::class);
    $state = ['order' => 731, 'probe' => [['value' => 'inert-data'], ['s' => 'wrbl', 'class' => HydrationProbe::class]]];
    HydrationProbe::$hydrations = 0;

    $wizard->{$transport}('showStep', 'third-step', $state)->assertSuccessful();

    expect($wizard->get('allStepState')['first-step'])->toBe($state)
        ->and(HydrationProbe::$hydrations)->toBe(0);

    $wizard->call('showStep', 'second-step')->assertSuccessful();

    expect(HydrationProbe::$hydrations)->toBe(0);
})->with(['call', 'dispatch']);

it('cannot turn caller supplied tuple data into trusted metadata using dispatchDehydrated', function () {
    $wizard = Livewire::test(MyWizardComponent::class);
    $state = [['probe' => [['value' => 'inert-data'], ['s' => 'wrbl', 'class' => HydrationProbe::class]]], ['s' => 'arr']];
    HydrationProbe::$hydrations = 0;

    Livewire::test(SecondStepComponent::class)
        ->call('dispatchDehydrated', 'showStep', 'third-step', $state)
        ->emitEvents()->in($wizard);

    expect($wizard->get('allStepState')['first-step'])->toBe($state)
        ->and(HydrationProbe::$hydrations)->toBe(0);
});

class HydrationProofRecord extends Model
{
    protected $guarded = [];

    public $timestamps = false;
}
