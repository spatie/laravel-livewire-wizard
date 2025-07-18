<?php

namespace Spatie\LivewireWizard\Components;

use Spatie\LivewireWizard\Components\Concerns\WizardHydratesState;

abstract class HydratedWizardComponent extends WizardComponent
{
    use WizardHydratesState;
}
