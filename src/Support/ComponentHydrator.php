<?php

namespace Spatie\LivewireWizard\Support;

use Livewire\Component;
use Livewire\Mechanisms\HandleComponents\ComponentContext;
use Livewire\Mechanisms\HandleSynths\HandleSynths;

class ComponentHydrator
{
    protected HandleSynths $synths;

    public function __construct()
    {
        $this->synths = app(HandleSynths::class);
    }

    public function dehydrateData(Component $component, mixed $data): mixed
    {
        $context = new ComponentContext($component);

        return $this->synths->dehydrate($data, $context, '');
    }

    public function hydrateData(Component $component, array $data): mixed
    {
        $context = new ComponentContext($component);

        return $this->synths->hydrate($data, $context, '');
    }
}
