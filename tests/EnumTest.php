<?php

use Spatie\LivewireWizard\Enums\StepStatus;

it('can handle enums', function () {
    expect(StepStatus::Current)->toBe(StepStatus::Current);
});
