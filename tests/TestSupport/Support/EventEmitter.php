<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Support;

use Illuminate\Support\Arr;
use Livewire\Testing\TestableLivewire;

class EventEmitter
{
    public function __construct(protected TestableLivewire $emittingComponent)
    {

    }

    public function in(TestableLivewire $component): TestableLivewire
    {
        $events = Arr::get($this->emittingComponent->payload, 'effects.emits');
        foreach($events as $event) {
            $component->emit($event['event'], $event['params']);
        }

        return $this->emittingComponent;
    }
}
