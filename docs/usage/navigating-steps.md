---
title: Navigating steps
weight: 2
---

There are various methods to navigate from one step to another. When switching steps we'll take care to preserve and restore state.

## Navigating to the next step

You can navigate to the next step, using `nextStep`. You can call that method anywhere in your step component.

```php
// somewhere in your step component

$this->nextStep();
```

You can also call it in your view.

```blade
<div wire:click="nextStep">
    Go to the next step
</div>
```

## Navigating to the previous step

You can navigate to the previous step, using `previousStep`. You can call that method anywhere in your step component.


```php
// somewhere in your step component

$this->previousStep();
```

You can also call it in your view.

```blade
<div wire:click="previousStep">
    Go to the previous step
</div>
```

## Showing any step

To show any step, call `showStep` and pass it the component name of the step you want to show.

```php
// somewhere in your step component

$this->showStep('confirm-order-step');
```

You can also call it in your view.

```blade
<div wire:click="showStep('confirm-order-step')">
    Go to the confirm order step
</div>
```

## Start at a specific step

If you want the wizard to display a specific step when it is rendered first, you can pass the step name to the `show-step` property.

```blade
<livewire:checkout-wizard show-step="delivery-address-step" />
```

## Skipping steps

In certain circumstances, you might want to skip over a few steps. This can be achieved by letting your step component implement a `shouldSkip` function. If it returns `true` then the step will be skipped.

```php
use Spatie\LivewireWizard\Components\StepComponent;

class DeliveryAddressStepComponent extends StepComponent
{
    public function shouldSkip(): bool
    {
        return auth()->user()->hasValidDeliveryAddress();
    }
}
```
