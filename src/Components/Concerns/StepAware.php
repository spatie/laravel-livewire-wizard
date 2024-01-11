<?php

namespace Spatie\LivewireWizard\Components\Concerns;

use Livewire\Mechanisms\ComponentRegistry;
use Spatie\LivewireWizard\Enums\StepStatus;
use Spatie\LivewireWizard\Support\Step;

trait StepAware
{
    public array $steps = [];

    public float $progress = 0;

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

        $this->getProgress();
    }

    public function getProgress(): float
    {
        $steps = collect($this->steps);
        $totalSteps = $steps->count() - 1;

        $index = $steps->search(fn (Step $step) => $step->stepName === $this->getName());

        return $this->progress = $totalSteps > 0
            ? round(abs($index / $totalSteps), 2)
            : 0;
    }
}
