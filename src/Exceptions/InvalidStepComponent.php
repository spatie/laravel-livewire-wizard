<?php

namespace Spatie\LivewireWizard\Exceptions;

use Exception;

class InvalidStepComponent extends Exception
{
    public static function doesNotExtendStepComponent(
        string $wizardComponentClassName,
        string $invalidStepComponentName
    ): self {
        return new self("The `steps` function of component `{$wizardComponentClassName}` did return an invalid step component `{$invalidStepComponentName}`. A valid step component should extend `StepComponent`.");
    }

    public static function notRegisteredWithLivewire(
        string $wizardComponentClassName,
        string $invalidStepComponentName
    ): self {
        return new self("The `steps` function of component `{$wizardComponentClassName}` did return step component `{$invalidStepComponentName}` that was not registered with Livewire.");
    }
}
