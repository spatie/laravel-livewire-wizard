<?php

namespace Spatie\LivewireWizard\Support;

use Illuminate\Support\Arr;
use Livewire\Features\SupportTesting\Testable;

class EventEmitter
{
    public function __construct(protected Testable $emittingComponent)
    {
    }

    public function in(Testable $component): Testable
    {
        $events = Arr::get($this->emittingComponent->effects, 'dispatches', []);

        foreach ($events as $event) {
            $component->dispatch($event['name'], ...$event['params']);
        }

        return $this->emittingComponent;
    }
}
