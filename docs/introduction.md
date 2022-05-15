---
title: Introduction
weight: 1
---

**PACKAGE IN DEVELOPMENT, DO NOT USE YET**

This package offers lightweight Livewire components that allow you to easily build a wizard. With "wizard" we mean a multi-step process in which each step has its own screen.  

Here's how a wizard component could look like.

```php
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

A step is class that extends `StepComponent` (which in its turn extends `Livewire\Component`). You can do anything in here that you can do with a regular Livewire component.

```php
namespace App\Components;

class CartStepComponent extends StepComponent
{
    // add any Livewire powered method you want

    public function render()
    {
        return view('checkout-wizard.steps.cart');
    }
}
```

You can easily [control which step is displayed](/docs/laravel-livewire-wizard/v1/usage/navigating-steps), [access state of other steps](/docs/laravel-livewire-wizard/v1/usage/accessing-state), and [build any navigation](/docs/laravel-livewire-wizard/v1/usage/rendering-navigation) you desire.

