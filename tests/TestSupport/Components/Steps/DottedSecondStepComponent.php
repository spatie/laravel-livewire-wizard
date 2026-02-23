<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class DottedSecondStepComponent extends StepComponent
{
    public function render()
    {
        return <<<'HTML'
        <div>dotted second step</div>
        HTML;
    }
}
