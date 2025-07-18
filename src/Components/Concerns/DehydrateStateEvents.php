<?php

namespace Spatie\LivewireWizard\Components\Concerns;

use Spatie\LivewireWizard\Support\ComponentHydrator;

trait DehydrateStateEvents
{
    public function dispatchDehydrated($event, ...$params)
    {
        $hydrator = app(ComponentHydrator::class);
        $newParams = collect($params)->map(fn($param) => $hydrator->dehydrateData($this, $param))->toArray();

        return parent::dispatch($event, ...$newParams);
    }

    public function previousStep()
    {
        $this->dispatchDehydrated('previousStep', $this->state()->currentStep())->to($this->wizardClassName);
    }

    public function nextStep()
    {
        $this->dispatchDehydrated('nextStep', $this->state()->currentStep())->to($this->wizardClassName);
    }

    public function showStep(string $stepName)
    {
        $this->dispatchDehydrated('showStep', toStepName: $stepName, currentStepState: $this->state()->currentStep())->to($this->wizardClassName);
    }
}
