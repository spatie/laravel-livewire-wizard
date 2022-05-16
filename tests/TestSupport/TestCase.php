<?php

namespace Spatie\LivewireWizard\Tests\TestSupport;

use DOMDocument;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Livewire\Testing\TestableLivewire;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\CollectionMacros\CollectionMacroServiceProvider;
use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SkipStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Support\EventEmitter;
use Spatie\LivewireWizard\WizardServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', '6rE9Nz59bGRbeMATftriyQjrpF7DcOQm');

        View::addNamespace('test', __DIR__ . '/resources/views');

        $this
            ->registerLivewireComponents()
            ->registerLivewireTestMacros();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            WizardServiceProvider::class,
            CollectionMacroServiceProvider::class,
        ];
    }

    private function registerLivewireComponents(): self
    {
        Livewire::component('wizard', WizardComponent::class);
        Livewire::component('first-step', FirstStepComponent::class);
        Livewire::component('second-step', SecondStepComponent::class);
        Livewire::component('third-step', ThirdStepComponent::class);
        Livewire::component('skip-step', SkipStepComponent::class);

        return $this;
    }

    public function registerLivewireTestMacros(): self
    {
        TestableLivewire::macro('emitEvents', function () {
            return new EventEmitter($this);
        });

        TestableLivewire::macro('jsonContent', function (string $elementId) {
            $document = new DOMDocument();

            $document->loadHTML($this->lastRenderedDom);

            $content = $document->getElementById($elementId)->textContent;

            return json_decode($content, true);
        });

        TestableLivewire::macro('htmlContent', function (string $elementId) {
            $document = new DOMDocument();

            $document->preserveWhiteSpace = false;

            $document->loadHTML($this->lastRenderedDom);

            $domNode = $document->getElementById($elementId);

            $html = $document->saveHTML($domNode);

            $html = str_replace("\r\n", "\n", $html);

            $html = Str::between($html, '<body>', '</body>');

            return trim($html);
        });

        return $this;
    }
}
