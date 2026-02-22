<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class DottedFirstStepComponent extends StepComponent
{
    public function render()
    {
        return <<<'HTML'
        <div>dotted first step</div>
        HTML;
    }
}
