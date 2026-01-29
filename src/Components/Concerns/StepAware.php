<?php

namespace Spatie\LivewireWizard\Components\Concerns;

use Spatie\LivewireWizard\Enums\StepStatus;
use Spatie\LivewireWizard\Support\ResolvesLivewireComponents;
use Spatie\LivewireWizard\Support\Step;

trait StepAware
{
    use ResolvesLivewireComponents;

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
}
