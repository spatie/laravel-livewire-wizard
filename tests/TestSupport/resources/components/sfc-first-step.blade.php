<?php

use Spatie\LivewireWizard\Components\StepComponent;

new class extends StepComponent
{
    public string $name = '';

    public function stepInfo(): array
    {
        return ['label' => 'First'];
    }
};
?>

<div>
    <p>sfc first step</p>
    <ul id="navigation">
        @foreach ($steps as $step)
            <li>{{ $step->label }} is {{ $step->status->value }}</li>
        @endforeach
    </ul>
</div>
