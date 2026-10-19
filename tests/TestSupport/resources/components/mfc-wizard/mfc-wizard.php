<?php

use Spatie\LivewireWizard\Components\WizardComponent;

new class () extends WizardComponent {
    public function steps(): array
    {
        return [
            'mfc-first-step',
            'sfc-second-step',
        ];
    }
};
