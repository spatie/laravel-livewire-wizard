<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\State;

use Spatie\LivewireWizard\Support\State;

class CustomState extends State
{
    public function foo(): string
    {
        return 'bar';
    }
}
