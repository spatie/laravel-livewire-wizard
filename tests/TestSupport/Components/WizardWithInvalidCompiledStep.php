<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;

class WizardWithInvalidCompiledStep extends WizardComponent
{
    public function steps(): array
    {
        return [
            'sfc-plain-component',
        ];
    }
}
