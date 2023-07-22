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

        $currentStepName = app(ComponentRegistry::class)->getName(static::class);

        $this->steps = collect($this->allStepNames)
            ->map(function (string $stepName) use (&$currentFound, $currentStepName) {
                $className = app(ComponentRegistry::class)->getClass($stepName);

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
}
