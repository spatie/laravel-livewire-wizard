<?php

use Spatie\LivewireWizard\Components\WizardComponent;

new class extends WizardComponent
{
    public function steps(): array
    {
        return [
            'sfc-first-step',
            'sfc-second-step',
            'sfc-third-step',
        ];
    }
};
?>

<div>
    <p>custom wizard view</p>

    @livewire($currentStepName, $currentStepState, key($currentStepName))
</div>
