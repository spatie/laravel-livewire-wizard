<?php

namespace Spatie\LivewireWizard\Tests\TestSupport;

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;
use Spatie\LivewireWizard\WizardServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        View::addNamespace('test', __DIR__ . '/resources/views');

        $this->registerLivewireComponents();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            WizardServiceProvider::class,
        ];
    }

    private function registerLivewireComponents()
    {
        Livewire::component('wizard', WizardComponent::class);
        Livewire::component('first-step', FirstStepComponent::class);
        Livewire::component('second-step', SecondStepComponent::class);
        Livewire::component('third-step', ThirdStepComponent::class);
    }
}
