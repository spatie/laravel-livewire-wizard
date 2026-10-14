---
title: Introduction
weight: 1
---

This package offers lightweight Livewire components that allow you to easily build a wizard. With "wizard" we mean a multi-step process in which each step has its own screen.  

![screenshot](/docs/laravel-livewire-wizard/v1/images/screenshot.png)

Single-file and multi-file components, as well as class-based components, are supported.

Here's what a wizard component could look like.

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

A step is a component that extends `StepComponent` (which in its turn extends `Livewire\Component`). You can do anything in here that you can do with a regular Livewire component.

```blade
{{-- resources/views/components/checkout/⚡cart-step.blade.php --}}
<?php

use Spatie\LivewireWizard\Components\StepComponent;

new class extends StepComponent
{
    // add any Livewire powered method you want
};
?>

<div>
    <ul>
        @foreach (session('cart') as $item)
            <li>{{ $item['name'] }}</li>
        @endforeach
    </ul>

    <button wire:click="nextStep">Continue</button>
</div>
```

Class-based components work the same way, [registered with Livewire](/docs/laravel-livewire-wizard/v1/usage/creating-your-first-wizard) and listed by class.

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
namespace App\Components;

use Spatie\LivewireWizard\Components\StepComponent;

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

In [this repo on GitHub](https://github.com/spatie/laravel-livewire-wizard-demo-app), you'll find a demo Laravel application that uses the laravel-livewire-wizard package to create a simple checkout flow.

