<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;

class WizardWithSfcSteps extends WizardComponent
{
    public function steps(): array
    {
        return [
            'sfc-first-step',
            'sfc-second-step',
        ];
    }
}
