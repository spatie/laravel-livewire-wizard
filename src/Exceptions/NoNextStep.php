<?php

namespace Spatie\LivewireWizard\Exceptions;

use Exception;

class NoNextStep extends Exception
{
    public static function make(
        string $wizardComponentClassName,
        string $requestingStepComponentName,
    ): self {
        return new static("The `{$requestingStepComponentName}` step of wizard `{$wizardComponentClassName}` requested to go to the next step, but there is no next step.");
    }
}
