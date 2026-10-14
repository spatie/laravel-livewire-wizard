<?php

use Spatie\LivewireWizard\Components\StepComponent;

new class extends StepComponent
{
    public function stepInfo(): array
    {
        return ['label' => 'Second'];
    }
};
?>

<div>
    <p>sfc second step</p>
    <p>hello {{ $this->state()->forStep('sfc-first-step')['name'] ?? 'nobody' }}</p>
</div>
