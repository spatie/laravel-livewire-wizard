<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components\Steps;

use Spatie\LivewireWizard\Components\StepComponent;

class StepWithValidationComponent extends StepComponent
{
    public string $name = '';

    public array $rules = [
        'name' => 'required',
    ];

    public function render()
    {
        return '';
    }
}
