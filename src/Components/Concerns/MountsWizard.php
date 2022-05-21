<?php

namespace Spatie\LivewireWizard\Components\Concerns;

use Spatie\LivewireWizard\Exceptions\InvalidStateClassName;
use Spatie\LivewireWizard\Support\State;

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

        if (! is_a($this->stateClass(), State::class, true)) {
            throw InvalidStateClassName::doesNotExtendState(static::class, $this->stateClass());
        };
    }
}
