<?php

namespace Spatie\LivewireWizard\Exceptions;

use Exception;

class NoPreviousStep extends Exception
{
    public static function make(
        string $wizardComponentClassName,
        string $requestingStepComponentName,
    ): self {
        return new static("The `{$requestingStepComponentName}` step of wizard `{$wizardComponentClassName}` requested to go to the previous step, but there is no previous step.");
    }
}
