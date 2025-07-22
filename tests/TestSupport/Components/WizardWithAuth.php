<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Components;

use Illuminate\Support\Facades\Gate;
use Spatie\LivewireWizard\Components\WizardComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\FirstStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\SecondStepComponent;
use Spatie\LivewireWizard\Tests\TestSupport\Components\Steps\ThirdStepComponent;

class WizardWithAuth extends WizardComponent
{
    public function mount(): void
    {
        // Instead of using a gate, I'm forcing an unauthorized action
        abort(403, 'Unauthorized action.');
    }

    public function steps(): array
    {
        return [
            FirstStepComponent::class,
            SecondStepComponent::class,
            ThirdStepComponent::class,
        ];
    }
}
