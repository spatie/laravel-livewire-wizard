<?php

namespace Spatie\LivewireWizard\Support;

use Illuminate\Support\Arr;
use Spatie\LivewireWizard\Enums\StepStatus;

class Step
{
    public function __construct(
        public string $stepName,
        public array $info,
        public StepStatus $status,
    ) {
    }

    public function isPrevious(): bool
    {
        return $this->status === StepStatus::Previous;
    }

    public function isCurrent(): bool
    {
        return $this->status === StepStatus::Current;
    }

    public function isNext(): bool
    {
        return $this->status === StepStatus::Next;
    }

    public function show(): string
    {
        return "showStep('{$this->stepName}')";
    }

    public function __get(string $key): mixed
    {
        return Arr::get($this->info, $key);
    }
}
