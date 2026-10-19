<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;

class WizardWithUnknownStepName extends WizardComponent
{
    public function steps(): array
    {
        return [
            'no-such-step',
        ];
    }
}
