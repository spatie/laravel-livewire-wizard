<?php

namespace Spatie\LivewireWizard\Support;

use Illuminate\Support\Arr;

class Step
{
    public function __construct(
        public string $stepName,
        public array $info,
        public string $status,
    ) {
    }

    public function isPrevious(): bool
    {
        return $this->status === 'previous';
    }

    public function isCurrent(): bool
    {
        return $this->status === 'current';
    }

    public function isNext(): bool
    {
        return $this->status === 'next';
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
