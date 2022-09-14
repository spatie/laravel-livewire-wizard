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
- `currentStep()`: returns an array containing the values of all public properties of the current step. The result is almost identical to Livewire's native `all()` method, but with some internal properties filtered out.

```php
// in a step component

$this->state()->all(); // returns all state from all steps in the wizard
$this->state()->forStep('delivery-address-step'); // returns all state of the given step
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
        $deliveryStepState = $this->forStep('delivery-address-step');
    
        return [
            'name' => $deliveryStepState['name'],
            'address' => $deliveryStepState['address'],
            'zip' => $deliveryStepState['zip'],
            'city' => $deliveryStepState['city'],
        ];
    }
}
```

Next, in you wizard class, you should add a method name `stateClass` and let it return the class name of your custom state. 

```php
use Spatie\LivewireWizard\Components\WizardComponent;

class CheckoutWizardComponent extends WizardComponent
{
    public function stateClass(): string
    {
        return MyCustomState::class;
    }
}
```

With this in place, the `state()` function of step components will return an instance of `MyCustomState`. You can use any custom method you added on your state class.

```php
namespace App\Components;

use Spatie\LivewireWizard\Components\StepComponent;

class ConfirmOrderStepComponent extends StepComponent
{
    public function render()
    {
        return view('checkout-wizard.steps.confirm', [
            'address' => $this->state()->deliveryAddress(),
        ]);
    }
}
```

