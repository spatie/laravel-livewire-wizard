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

@include('livewire-wizard::wizard')
