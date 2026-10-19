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
        Component::macro('testStep', function (string $stepClass, array $state = [], array $params = []) {
            return Livewire::test(static::class, array_merge(['initialState' => $state], $params))
                ->testStep($stepClass, $state);
        });

        Testable::macro('testStep', function (string $step, array $state = []) {
            $stepName = app('livewire.finder')->normalizeName($step);
            $wizard = $this->instance();

            foreach ($state as $stateStepName => $stepState) {
                $wizard->setStepState($stateStepName, $stepState);
            }

            $this->set([
                'allStepState' => $wizard->allStepState,
                'currentStepName' => $stepName,
            ]);

            return Livewire::test($step, $this->instance()->getCurrentStepState($stepName))
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
