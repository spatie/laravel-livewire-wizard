<?php

namespace Spatie\LivewireWizard\Support;

trait ResolvesLivewireComponents
{
    private function componentName(string $name): string
    {
        return app('livewire.finder')->normalizeName($name);
    }

    private function componentClass(string $name): string
    {
        return app('livewire.finder')->resolveClassComponentClassName($name);
    }
}
