<?php

namespace Spatie\Wizard;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Wizard\Commands\WizardCommand;

class WizardServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-livewire-wizard')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel-livewire-wizard_table')
            ->hasCommand(WizardCommand::class);
    }
}
