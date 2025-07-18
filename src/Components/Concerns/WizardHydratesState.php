<?php

namespace Spatie\LivewireWizard\Components\Concerns;

use Livewire\Attributes\On;
use Livewire\Drawer\Utils;
use Spatie\LivewireWizard\Support\ComponentHydrator;

trait WizardHydratesState
{
    #[On('showStep')]
    public function showStep($toStepName, array $currentStepState = [])
    {
        if ($this->currentStepName) {
            $state = $currentStepState;
            if (Utils::isSyntheticTuple($state)) {
                $state = app(ComponentHydrator::class)->hydrateData($this, $currentStepState);
            }

            $this->setStepState($this->currentStepName, $currentStepState);
        }


        $this->currentStepName = $toStepName;
    }
}
