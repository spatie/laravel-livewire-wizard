<?php

namespace Spatie\LivewireWizard\Components;

use Spatie\LivewireWizard\Components\Concerns\DehydrateStateEvents;

abstract class DehydratedStepComponent extends StepComponent
{
    use DehydrateStateEvents;
}
