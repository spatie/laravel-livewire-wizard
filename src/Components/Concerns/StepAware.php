<?php

namespace Spatie\LivewireWizard\Components\Concerns;

use Livewire\Mechanisms\ComponentRegistry;
use Spatie\LivewireWizard\Enums\StepStatus;
use Spatie\LivewireWizard\Support\Step;

trait StepAware
{
    public array $steps = [];

    public function bootedStepAware()
    {
        $currentFound = false;

        $currentStepName = $this->componentName(static::class);

        $this->steps = collect($this->allStepNames)
            ->map(function (string $stepName) use (&$currentFound, $currentStepName) {
                $className = $this->componentClass($stepName);

                $info = (new $className())->stepInfo();

                $status = $currentFound ? StepStatus::Next : StepStatus::Previous;


                if ($stepName === $currentStepName) {
                    $currentFound = true;
                    $status = StepStatus::Current;
                }

                return new Step($stepName, $info, $status);
            })
            ->toArray();
    }

    private function componentName(string $name): string
    {
        if (app()->has(ComponentRegistry::class)) {

            return app(ComponentRegistry::class)->getName($name);
        }

        return app('livewire.finder')->normalizeName($name);
    }

    private function componentClass(string $name): string
    {
        if (app()->has(ComponentRegistry::class)) {

            return app(ComponentRegistry::class)->getClass($name);
        }

        return app('livewire.finder')->resolveClassComponentClassName($name);
    }
}
