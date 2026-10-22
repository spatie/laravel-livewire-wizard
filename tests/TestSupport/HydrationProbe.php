<?php

namespace Spatie\LivewireWizard\Tests\TestSupport;

use Livewire\Wireable;

class HydrationProbe implements Wireable
{
    public static int $hydrations = 0;
    public static int $constructions = 0;

    public function __construct(public string $value = 'local marker')
    {
        self::$constructions++;
    }

    public function toLivewire(): array
    {
        return ['value' => $this->value];
    }

    public static function fromLivewire($value): self
    {
        self::$hydrations++;

        return new self($value['value']);
    }
}
