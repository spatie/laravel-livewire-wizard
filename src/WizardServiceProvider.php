<?php

namespace Spatie\LivewireWizard;

use Livewire\Testing\TestableLivewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LivewireWizard\Support\EventEmitter;

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
        $this->registerLivewireTestMacros();
    }

    public function registerLivewireTestMacros()
    {
        TestableLivewire::macro('emitEvents', function () {
            return new EventEmitter($this);
        });

        TestableLivewire::macro('getStepState', function (?string $step = null) {
            return $this->instance()->getCurrentStepState($step);
        });
    }
}
