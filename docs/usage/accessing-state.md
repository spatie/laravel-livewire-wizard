---
title: Accessing state
weight: 4
---

In any of your step components you can access the state of the all other steps in the wizard. You can call the `state()` function in a step component, to get back an instance of `Spatie\LivewireWizard\Support\State`.

```php
// in a step component

$this->state(); // returns instance of `Spatie\LivewireWizard\Support\State`
```

On that state object you can call these methods:

- `all()`: returns an array containing the values of all public properties of all steps in the wizard. The key of the items in the array is the name of the step. Optionally, you can pass is a key name to let the function only return the value for that key.
- `forStep($stepname)`:  returns the values of all public properties of the given step.
- `forStepClass(StepComponent::class)`: similar to `forStep`, for a class-based step listed by its class name.
- `currentStep()`: returns an array containing the values of all public properties of the current step. The result is almost identical to Livewire's native `all()` method, but with some internal properties filtered out.

```php
// in a step component

$this->state()->all(); // returns all state from all steps in the wizard
$this->state()->forStep('checkout.delivery-address-step'); // returns all state of the given step
$this->state()->forStepClass(App\Livewire\DeliveryAddressStep::class); // for a class-based step
$this->state()->currentStep(); // returns all state of the current step
```

## Using a custom state object

By default, calling `$this->state()` in a step component will return an instance of `Spatie\LivewireWizard\Support\State`. Optionally, you can customize that class, so you can add little helper methods on your state class.

To get started, first create a class that extends `Spatie\LivewireWizard\Support\State`. You can add any method that you want on that custom class.

```php
use Spatie\LivewireWizard\Support\State;

class MyCustomState extends State
{
    public function deliveryAddress(): array
    {
        $deliveryStepState = $this->forStep('checkout.delivery-address-step');
    
        return [
            'name' => $deliveryStepState['name'],
            'address' => $deliveryStepState['address'],
            'zip' => $deliveryStepState['zip'],
            'city' => $deliveryStepState['city'],
        ];
    }
}
```

Next, in your wizard component, you should add a method named `stateClass` and let it return the class name of your custom state.

```blade
{{-- resources/views/components/⚡checkout-wizard.blade.php --}}
<?php

use App\Support\MyCustomState;
use Spatie\LivewireWizard\Components\WizardComponent;

new class extends WizardComponent
{
    public function stateClass(): string
    {
        return MyCustomState::class;
    }

    // ...
};
?>

@include('livewire-wizard::wizard')
```

With this in place, the `state()` function of step components will return an instance of `MyCustomState`. You can use any custom method you added on your state class.

```blade
{{-- resources/views/components/checkout/⚡confirm-order-step.blade.php --}}
<?php

use Spatie\LivewireWizard\Components\StepComponent;

new class extends StepComponent
{
};
?>

<div>
    Deliver to {{ $this->state()->deliveryAddress()['name'] }}
</div>
```

