<?php

namespace Spatie\Wizard\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spatie\Wizard\Wizard
 */
class Wizard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravel-livewire-wizard';
    }
}
