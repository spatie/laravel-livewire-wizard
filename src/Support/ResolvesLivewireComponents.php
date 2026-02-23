<?php

namespace Spatie\LivewireWizard\Support;

trait ResolvesLivewireComponents
{
    private function componentName(string $name): ?string
    {
        return app('livewire.finder')->normalizeName($name);
    }

    private function componentClass(string $name): string
    {
        [, $class] = app('livewire.factory')->resolveComponentNameAndClass($name);

        return $class;
    }
}
