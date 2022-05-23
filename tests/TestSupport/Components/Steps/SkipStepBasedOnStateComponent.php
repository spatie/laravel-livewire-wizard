<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class SkipStepBasedOnStateComponent extends StepComponent
{
    public bool $shouldSkipDecider = false;

    public function shouldSkip(): bool
    {
        return $this->shouldSkipDecider;
    }

    public function render()
    {
        return <<<'blade'
            <div>
            skip based on state
            </div>
        blade;
    }
}
