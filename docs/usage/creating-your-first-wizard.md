---
title: Creating your first wizard
weight: 1
---

A "wizard" is a multi-step process in which each step has its own screen. In our implementation, each step will be its own Livewire `StepComponent`. These step components will be tied together using a `WizardComponent`.

## Creating the wizard component

To get started you need to create a class that extends `WizardComponent`.

```php
namespace App\Components;

use Spatie\LivewireWizard\Components\WizardComponent;

class CheckoutWizardComponent extends WizardComponent
{

}
```

The `WizardComponent` class extends Livewire's component class, so you need to register the `CheckoutWizardComponent` with Livewire.

```php
// typically, in a service provider

use Livewire\Livewire;
use App\Components\CheckoutWizardComponent;

Livewire::component('checkout-wizard', CheckoutWizardComponent::class);
```

## Creating steps

Next, let's add steps to the wizard. In our example, let's assume the checkout process has three steps:

1. A step to specify display the contents of a cart
2. A step to specify delivery address details
3. A step that show all order details and the ability to confirm the order

For each step, you need to create a class that extends `StepComponent`. Here's how it may look like for the first step of our example.

```php
namespace App\Components;

use Spatie\LivewireWizard\Components\StepComponent;

class CartStepComponent extends StepComponent
{
    public function render()
    {
        return view('checkout-wizard.steps.cart');
    }
}
```

This `CartComponent` is a regular Livewire component, so you can add any Livewire functionality you want. You could display some info, add actions, handle a form, anything goes!

Since steps are Livewire components, don't forget to register all steps to Livewire.

```php
// typically, in a service provider

use Livewire\Livewire;
use App\Components\CartStepComponent;
use App\Components\DeliveryAddressStepComponent;
use App\Components\ConfirmOrderStepComponent;

// ... other registrations

Livewire::component('cart-step', CartStepComponent::class);
Livewire::component('delivery-address-step', DeliveryAddressStepComponent::class);
Livewire::component('confirm-order-step', ConfirmOrderStepComponent::class);
```

## Adding steps to the wizard

Now that you've created the step classes, let's add them to the wizard.

In `CheckoutWizardComponent` add a function named `steps` that returns an array with all your steps.

```php
namespace App\Components;

use App\Components\CartStepComponent;
use App\Components\DeliveryAddressStepComponent;
use App\Components\ConfirmOrderStepComponent;
use Spatie\LivewireWizard\Components\WizardComponent;

class CheckoutWizardComponent extends WizardComponent
{
    public function steps() : array
    {
        return [
            CartStepComponent::class,
            DeliveryAddressStepComponent::class,
            ConfirmOrderStepComponent::class,
        ];       
    }
}
```

## Rendering the wizard component

Now that everything is set up, you can render the wizard component in any view you desire.

```blade
<div>
    <livewire:checkout-wizard />
</div>
```

## Going to the next step in the wizard

When navigating to the view, you should now see the first step of the wizard being rendered. If you want to next step to be displayed, you can call `nextStep()` somewhere in your livewire component.

```php
// somewhere in your step component

$this->nextStep();
```

When that code is executed you should see the next step being rendered in the browser.

Alternatively, you could also directly use `nextStep` in a `wire:click` somewhere in your view.

```blade
<div wire:click="previousStep">
    Go to the previous step
</div>

<div wire:click="nextStep">
    Go to the next step
</div>
```

With the basics of the wizard now working, explore the other sections in these docs to explore what's possible.

## Using SFCs and MFCs

Wizards and steps can also be Livewire single-file or multi-file components. These need no registration, since Livewire finds them by name, but they have no class name either, so list the steps by their component names.

```blade
{{-- resources/views/components/checkout-wizard.blade.php --}}
<?php

use Spatie\LivewireWizard\Components\WizardComponent;

new class extends WizardComponent
{
    public function steps(): array
    {
        return [
            'checkout.cart-step',
            'checkout.delivery-address-step',
            'checkout.confirm-order-step',
        ];
    }
};
?>

@include('livewire-wizard::wizard')
```

A single-file component always has a template, so the wizard includes the package's view to render the current step. To render something around the step instead, use `$currentStepName` and `$currentStepState` in your own template.

```blade
<div>
    <h1>Checkout</h1>

    {{-- The current step --}}
    @livewire($currentStepName, $currentStepState, key($currentStepName))
</div>
```

A step is a single-file component that extends `StepComponent`.

```blade
{{-- resources/views/components/checkout/cart-step.blade.php --}}
<?php

use Spatie\LivewireWizard\Components\StepComponent;

new class extends StepComponent
{
    public function save(): void
    {
        $this->nextStep();
    }
};
?>

<div>
    ...
</div>
```

A multi-file component works the same way, with the class and the template in separate files.

```php
// resources/views/components/checkout-wizard/checkout-wizard.php

use Spatie\LivewireWizard\Components\WizardComponent;

new class extends WizardComponent
{
    public function steps(): array
    {
        return [
            'checkout.cart-step',
            'checkout.delivery-address-step',
            'checkout.confirm-order-step',
        ];
    }
};
```

```blade
{{-- resources/views/components/checkout-wizard/checkout-wizard.blade.php --}}
@include('livewire-wizard::wizard')
```

Component names and class names can be mixed in the wizard's `steps()`. When accessing the state of a step by name, use `forStep()` rather than `forStepClass()`.

## Take a look at the example app

In [this repo on GitHub](https://github.com/spatie/laravel-livewire-wizard-demo-app), you'll find a demo Laravel application that contains the checkout flow as described above.
