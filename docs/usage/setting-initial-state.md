---
title: Setting initial state
weight: 5
---

There are various ways to customize what your wizard should initially display.

## Starting at a specific step

If you want to let your wizard display a specific step when it is first rendered, you can pass the step name to the `show-step` property of the wizard.

```blade
<livewire:checkout-wizard show-step="confirm-order" />
```

## Setting initial state

To let the steps start with other values than the ones hardcoded in your steps, you can pass initial state via the `initial-state` property. The value given must be an array. The key of each item needs to be the name of a step, the value an array containing the state for that step. Here's an example:

```blade
@php
$initialState = [
    'delivery-address-step' => [
        // ...
        'zip' => '10000'
        'city' => 'Washington',
    ],
];

@endphp

<livewire:checkout-wizard show-step="confirm-order" :initial-state="$initialState" />
```

Instead of passing initial state via the `initial-state` property, you could let your `WizardComponent` implement 
`initialState` and let that function return the initial state.

In this example we'll pass the user id as a prop, and fetch the relevant details in the `initialState` function.

```blade
{{-- in your blade view --}}
<livewire:checkout-wizard show-step="confirm-order" user-id="$userId",  />
```

```php
namespace App\Components;

use Spatie\LivewireWizard\Components\WizardComponent;

class CheckoutWizardComponent extends WizardComponent
{
    public function mount(string $userId)
    {
        $this->userId = $userId;
    }
    
    public function initialState(): array
    {
        $user = User::findOrFail($this->userId);
        
        return [
            'delivery-address-step' => [
                'zip' => $user->zip,
                'city' => $user->city,
            ],       
        ];
    }
}
```



