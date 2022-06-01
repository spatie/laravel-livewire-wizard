<?php

namespace Spatie\LivewireWizard\Exceptions;

use Exception;

class StepDoesNotExist extends Exception
{
    public static function stepNotFound(string $nonExistingStepName): self
    {
        return new static("Step `{$nonExistingStepName}` does not exist.");
    }

    public static function doesNotHaveState(string $nonExistingStepName): self
    {
        return new static("Step `{$nonExistingStepName}` step does not exist and as such cannot have state.");
    }
}
