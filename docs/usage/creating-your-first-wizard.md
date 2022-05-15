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

The `WizardComponent` class extends Livewire's component class, so you need the register `CheckoutWizardComponent` with
Livewire.

```php
// typically, in a service provider

use Livewire\Livewire;
use App\Components\App\Components;

Livewire::component('checkout-wizard', CheckoutWizardComponent::class);
```

## Creating steps

Next, let's add steps to the wizard. In our example, let's assume the checkout process has three steps:

1. A step to specify display the contents of a cart
2. A step to specify delivery address details
3. A step that show all order details and the ability to confirm the order

For each step, you need to create a class that extends `StepComponent`. Here's how it may look like for the first step
of our example.

```php
namespace App\Components;

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
use App\Components\CartComponent;
use App\Components\DeliveryAddressComponent;
use App\Components\ConfirmOrderComponent;

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

use App\Components\CartComponent;
use App\Components\DeliveryAddressComponent;
use App\Components\ConfirmOrderComponent;
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
