<?php

use Spatie\LivewireWizard\Components\StepComponent;

new class extends StepComponent
{
    public function stepInfo(): array
    {
        return ['label' => 'Third'];
    }
};
?>

<div>
    <p>sfc third step</p>
    <ul id="navigation">
        @foreach($steps as $step)
            <li>{{ $step->label }} is {{ $step->status->value }}</li>
        @endforeach
    </ul>
</div>
