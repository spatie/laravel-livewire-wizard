<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Spatie\LivewireWizard\Components\WizardComponent;
use stdClass;

class WizardWithNonComponentStep extends WizardComponent
{
    public function steps(): array
    {
        return [
            stdClass::class,
        ];
    }
}
