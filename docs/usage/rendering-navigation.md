---
title: Rendering navigation
weight: 3
---

Any step component has a `$steps` property that contains an array containing information on all steps in the wizard. You can use `$steps` to build any navigation you want. Here's an example:

```blade
{{-- somewhere in a Blade view--}}
<ul>
    @foreach($steps as $step)
        <li 
            class="{{ $step->isCurrent() ? 'text-bold' : '' }}"
            @if ($step->isPrevious())
                wire:click="{{ $step->show() }}"
            @endif
        >{{ $step->label }}</li>
    @endforeach
</ul>
```

## Available methods

Each entry in the array contains an instance of `Spatie\LivewireWizard\Support\Step`. It has these methods:

- `isCurrent()`: returns `true` is this step is currently being displayed
- `isPrevious()`: returns `true` is this step comes before the step that's currently displayed
- `isNext()`: returns `true` is this step comes after the step that's currently displayed
- `show()`: return the string that can be passed to `wire:click` to show the step

## Adding extra info to a step

In the example above, you see that we've used `$step->label` to render the content of the `<li>`.
That `label` property isn't available by default.

You can add any property on a step by adding a `stepInfo` method your `Step` component. That method should contain an array with properties regarding your step.

```php
// in your step component

public function stepInfo(): array
{
    return [
        'label' => 'Your cart',
        'icon' => 'fa-shopping-cart',  
    ];
}
```

Any key you return will be available as a property on step.

```php
$step->label; // returns 'Your cart'
$step->icon; // returns 'fa-shopping-cart'
```

