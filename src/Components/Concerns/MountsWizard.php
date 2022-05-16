<?php

namespace Spatie\LivewireWizard\Components\Concerns;

trait MountsWizard
{
    public function mountMountsWizard(?string $showStep = null, array $initialState = null)
    {
        $stepName = $showStep ?? $this->stepNames()->first();

        $initialState = $initialState ?? $this->initialState() ?? [];

        $this->showStep($stepName, $initialState[$stepName] ?? []);

        foreach ($initialState as $stepName => $state) {
            $this->setStepState($stepName, $state);
        }
    }
}
