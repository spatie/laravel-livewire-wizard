<?php

namespace Spatie\LivewireWizard\Support;

use Livewire\Component;
use Livewire\Mechanisms\HandleComponents\ComponentContext;
use Livewire\Mechanisms\HandleComponents\HandleComponents;

class ComponentHydrator extends HandleComponents
{
    public function __construct()
    {
        $this->propertySynthesizers = app(HandleComponents::class)->propertySynthesizers;
    }

    public function dehydrateData(Component $component, mixed $data): mixed
    {
        $context = new ComponentContext($component);
        $data = $this->dehydrate($data, $context, '');

        return $data;
    }

    public function hydrateData(Component $component, array $data): mixed
    {
        $context = new ComponentContext($component);
        $data = $this->hydrate($data, $context, '');

        return $data;
    }
}
