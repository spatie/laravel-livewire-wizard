<?php

namespace Spatie\LivewireWizard\Support;

use Livewire\Mechanisms\ComponentRegistry;

/**
 * Provides Livewire component resolution that works with both Livewire 3 and 4.
 *
 * Livewire 4 replaced ComponentRegistry with a Finder (aliased as 'livewire.finder').
 */
trait ResolvesLivewireComponents
{
    private function componentName(string $name): string
    {
        if (app()->has(ComponentRegistry::class)) {
            return app(ComponentRegistry::class)->getName($name);
        }

        return app('livewire.finder')->normalizeName($name);
    }

    private function componentClass(string $name): string
    {
        if (app()->has(ComponentRegistry::class)) {
            return app(ComponentRegistry::class)->getClass($name);
        }

        return app('livewire.finder')->resolveClassComponentClassName($name);
    }
}
