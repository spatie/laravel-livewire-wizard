---
title: Rendering navigation
weight: 2
---

Any step component has a `$steps` property that contains an array containing information on all steps in the wizard.

Each entry in the array contains an instance of ``

```blade
<ul>
    @foreach($steps as $step)
        <li class="{{ $step->isCurrent() ? 'text-bold' : '' }}"
            @if ($step->isPrevious())
                wire:click="{{ $step->show() }}"
            @endif
        >

            {{ $step->label }}</li>
    @endforeach
</ul>
```
