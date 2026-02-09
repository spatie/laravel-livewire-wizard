---
title: Testing wizards
weight: 6
---

On this page we'll show you a few tips on how to test wizard steps created with this package. For explanation purposes, consider an example wizard called `CheckoutWizardComponent`. It has the following steps:
- `CartStepComponent`: Displays the products in the user's cart.
- `DeliveryAddressStepComponent`: Allows the user to enter their delivery address.
- `ConfirmOrderStepComponent`: Displays the order details and allows the user to confirm the order.

## Testing a wizard step without state
The first step of our checkout wizard is a simple step that doesn't require any state. It only displays the products in the user's cart. To test this step, we can use the following test:

```php
session()->put('cart', [
    ['name' => 'Candle', 'price' => 400],
    ['name' => 'Chocolate', 'price' => 150],
]);

CheckoutWizardComponent::testStep(CartStepComponent::class)
    ->assertSuccessful()
    ->assertSee('Items in your cart')
    ->assertSee('Candle')
    ->assertSee('Chocolate');
```

The above test will assert that the step is shown successfully, and that it displays the products in the cart.

## Testing a wizard step with state
You may need to test a wizard step that requires some state from a previous step. As an example, consider the last step of our checkout wizard.  

```php
CheckoutWizardComponent::testStep(ConfirmOrderStepComponent::class, [
        'wizard.delivery-address-step-component' => [
            'street' => '1818 Sherman Street',
            'city' => 'Hope',
            'state' => 'Kansas',
            'zip' => '67451',
            'deliveryDate' => '2022-01-12', // Wednesday
        ],
    ])
    ->assertSuccessful()
    ->assertSee('Please confirm your order')
    ->assertSee('Delivery Address: 1818 Sherman Street, Hope, Kansas, 67451')
    ->assertSee('Delivery on Wednesday, 12th January 2022')
    ->call('confirmOrder');
    
// Assert that the order is created and other expectations...
expect(Order::count())->toBe(1);
```

In the above test, we pass the state required by the `ConfirmOrderStepComponent` as an array. In this case, it is the delivery address that the user entered in the previous step. We are then able to assert whether the text is displayed properly and call the `confirmOrder` method on the Step to submit the order.

## Testing a wizard step with mount parameters
If your wizard component accepts parameters in its `mount` method, you can pass them using the `params` argument:

```php
CheckoutWizardComponent::testStep(CartStepComponent::class, params: [
    'user' => $user,
    'mode' => 'edit',
])
    ->assertSuccessful()
    ->assertSee('Items in your cart');
```

These parameters will be forwarded to the `mount` method of the wizard component:

```php
class CheckoutWizardComponent extends WizardComponent
{
    public function mount(User $user, string $mode): void
    {
        $this->user = $user;
        $this->mode = $mode;
    }

    // ...
}
```

You can also combine state and mount parameters:

```php
CheckoutWizardComponent::testStep(ConfirmOrderStepComponent::class, [
        'wizard.delivery-address-step-component' => [
            'street' => '1818 Sherman Street',
        ],
    ], params: [
        'user' => $user,
    ])
    ->assertSuccessful();
```
