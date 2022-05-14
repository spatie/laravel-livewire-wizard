<?php

namespace Spatie\LivewireWizard\Exceptions;

use Exception;

class StepDoesNotExist extends Exception
{
    public static function make(string $askingStepName, string $nonExistingStepName): self
    {
        return new static("Step `$askingStepName` tried to activate step `{$nonExistingStepName}`, but that step does not exist.");
    }
}
