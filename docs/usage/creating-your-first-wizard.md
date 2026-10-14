---
title: Creating your first wizard
weight: 1
---

A "wizard" is a multi-step process in which each step has its own screen. In our implementation, each step will be its own Livewire `StepComponent`. These step components will be tied together using a `WizardComponent`.

## Creating the wizard component

To get started, create a single-file component that extends `WizardComponent`. Livewire finds it by its name, `checkout-wizard`, so there is nothing to register.

```blade
{{-- resources/views/components/⚡checkout-wizard.blade.php --}}
<?php

use Spatie\LivewireWizard\Components\WizardComponent;

new class extends WizardComponent
{
};
?>

@include('livewire-wizard::wizard')
```

A single-file component always has a template. Here the wizard includes the package's view, which renders the current step; we'll come back to that template when rendering the wizard.

## Creating steps

Next, let's add steps to the wizard. In our example, let's assume the checkout process has three steps:

1. A step to display the contents of a cart
2. A step to specify delivery address details
3. A step that shows all order details and the ability to confirm the order

For each step, create a single-file component that extends `StepComponent`. Here's how it may look like for the first step of our example.

```blade
{{-- resources/views/components/checkout/⚡cart-step.blade.php --}}
<?php

use Spatie\LivewireWizard\Components\StepComponent;

new class extends StepComponent
{
};
?>

<div>
    ...
</div>
```

This step is a regular Livewire component, so you can add any Livewire functionality you want. You could display some info, add actions, handle a form, anything goes!

## Adding steps to the wizard

Now that you've created the steps, let's add them to the wizard.

In the wizard, add a function named `steps` that returns an array with the component names of all your steps.

```blade
{{-- resources/views/components/⚡checkout-wizard.blade.php --}}
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

## Rendering the wizard component

Now that everything is set up, you can render the wizard component in any view you desire.

```blade
<div>
    <livewire:checkout-wizard />
</div>
```

The wizard's template is what renders the current step, which is all the package's view does. To render something around the step instead, use `$currentStepName` and `$currentStepState` in your own template.

```blade
<div>
    <h1>Checkout</h1>

    {{-- The current step --}}
    @livewire($currentStepName, $currentStepState, key($currentStepName))
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

## Using multi-file components

A multi-file component works the same way, with the class and the template in separate files.

```php
// resources/views/components/⚡checkout-wizard/checkout-wizard.php

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
{{-- resources/views/components/⚡checkout-wizard/checkout-wizard.blade.php --}}
@include('livewire-wizard::wizard')
```

## Using class-based components

Wizards and steps can also be class-based components. These need to be registered with Livewire, and can be listed in `steps()` by class name. Component names and class names can be mixed.

```php
namespace App\Components;

use Spatie\LivewireWizard\Components\WizardComponent;

class CheckoutWizardComponent extends WizardComponent
{
    public function steps(): array
    {
        return [
            CartStepComponent::class,
            DeliveryAddressStepComponent::class,
            ConfirmOrderStepComponent::class,
        ];
    }
}
```

```php
// typically, in a service provider

use Livewire\Livewire;
use App\Components\CheckoutWizardComponent;
use App\Components\CartStepComponent;
use App\Components\DeliveryAddressStepComponent;
use App\Components\ConfirmOrderStepComponent;

Livewire::component('checkout-wizard', CheckoutWizardComponent::class);
Livewire::component('cart-step', CartStepComponent::class);
Livewire::component('delivery-address-step', DeliveryAddressStepComponent::class);
Livewire::component('confirm-order-step', ConfirmOrderStepComponent::class);
```

A class-based step renders its own view, and a class-based wizard without a `render` method uses the package's view.

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

When accessing the state of a step listed by class, `forStepClass()` works as well as `forStep()`.

## Take a look at the example app

In [this repo on GitHub](https://github.com/spatie/laravel-livewire-wizard-demo-app), you'll find a demo Laravel application that contains the checkout flow as described above.
