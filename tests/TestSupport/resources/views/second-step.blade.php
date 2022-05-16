<div>
    This is the second step
    counter: {{ $this->counter }}

    {{-- used in ste test --}}
    <div id="currentStepState">@json($currentStepState)</div>
    <div id="allStepState">@json($allStepState)</div>

    {{-- used in navigation test --}}
    <div id="navigation">
        This is navigation
        <ul>
            @foreach($steps as $step)
                In step
                <li
                    class="{{ $step->isCurrent() ? 'text-bold' : '' }}"
                    @if ($step->isPrevious())
                        wire:click="{{ $step->show() }}"
                    @endif
                >{{ $step->label }}</li>
            @endforeach
        </ul>
    </div>
</div>
