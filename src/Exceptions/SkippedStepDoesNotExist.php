<?php

namespace Spatie\LivewireWizard\Exceptions;

use Exception;

class SkippedStepDoesNotExist extends Exception
{
    public static function make(
        string $wizardComponentClassName,
        string $requestingStepComponentName,
    ): self {
        return new static("The `{$requestingStepComponentName}` step of wizard `{$wizardComponentClassName}` requested to go to skip the next step, but there is no next step.");
    }
}
