<?php

namespace Spatie\LivewireWizard;

use Livewire\Component;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LivewireWizard\Support\EventEmitter;
use Spatie\LivewireWizard\Support\StepSynth;

class WizardServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-livewire-wizard')
            ->hasViews();
    }

    public function bootingPackage()
    {
        Livewire::propertySynthesizer(StepSynth::class);
        $this->registerLivewireTestMacros();
    }

    public function registerLivewireTestMacros()
    {
        Component::macro('testStep', function (string $step, array $state = [], array $params = []) {
            return Livewire::test(static::class, array_merge(['initialState' => $state], $params))
                ->testStep($step, $state);
        });

        Testable::macro('testStep', function (string $step, array $state = []) {
            $wizard = $this->invade();
            $wizard->allStepState = [];
            $wizard->mountMountsWizard($step, $state);

            return Livewire::test($step, $wizard->getCurrentStepState($step))
                ->emitEvents()->in($this);
        });

        Testable::macro('emitEvents', function () {
            return new EventEmitter($this);
        });

        Testable::macro('getStepState', function (?string $step = null) {
            return $this->instance()->getCurrentStepState($step);
        });
    }
}
