<?php

namespace Spatie\LivewireWizard\Components\Concerns;

trait MountsWizard
{
    public function mountMountsWizard(?string $showStep = null, array $initialState = [])
    {
        $stepName = $showStep ?? $this->stepNames()->first();

        $initialState = $this->initialState() ?? $initialState;

        $this->showStep($stepName, $initialState[$stepName] ?? []);

        foreach ($initialState as $stepName => $state) {
            $this->setStepState($stepName, $state);
        }
    }
}
