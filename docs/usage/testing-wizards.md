---
title: Testing wizards
weight: 6
---

Livewire has an extensive set of testing utilities; we offer a few more to 
test your wizards.

## Internals

Let's briefly touch on some of the internals.

A wizard keeps track of its steps. Navigating through a wizard is based on
events. Once you `nextStep` in one of your steps, it emits up to your wizard, 
with the state of that step as its parameter.

When the next step is loaded, its state gets passed by the wizard. The 
`StepComponent` will asign state to its properties.

Testing your wizard needs some magic. Because well, wizards do magic.

## Navigating your wizard

`emitEvents` allows you to take all events from a `StepComponent` and make
them available for your wizard. Without it, you would only be able to test
your components by themselves.

Below is an example of its use; we use something similar in our own tests.

```php
$wizard = Livewire::test(CartWizard::class);

$wizard->assertSee('cart');

Livewire::test(ShowCartStep::class)
    ->call('nextStep')
    ->emitEvents()->in($wizard);

$wizard->assertSee('fill in your address');
```

ShowCartStep shows the contents of a customers cart and is the first step of
our wizard. The next step, although the class is not shown here, is to fill in
your address details.

The wizard renders each step, so we first assert if the first step has been
loaded correctly. A simple test is to assert that a string is visible. In this
case, we assert that it shows 'cart'.

We then move to the next step by calling `nextStep`. This emits an event to be
picked up by the wizard. This usually depends on JavaScript in the browser, to
simulate this in your test, you can use `emitEvents`.

`emitEvents` takes the event thats emitted by `nextStep` and passes it to the
wizard. The wizard processes it and takes you to the next step.

We then assert if the second step is loaded.

## Testing state in a StepComponent

Now you know how to navigate your wizard in your tests, let's talk state. 

We go back to our cart wizard. We want to test state. Our example is a simple
one, but this can be used to test your final step where you're processing your
cart.

We're going to emulate ordering Spatie's Laravel Comments. `initialState` is 
used to populate the cart. We're not implementing the address step here and
go straight to checkout.

```php
$initialState = 'show-cart-step' => [
    'items' => [
        0 => [
            'detail' => 'Laravel Comments'
        ],
    ],
],

$wizard = Livewire::test(CartWizard::class, [
    'initialState' => $initialState,  
]);

$showCartState = $wizard->getStepState('show-cart-step');

Livewire::test(ShowCartStep::class, $showCartState)
    ->assertSet('items.0.detail', 'Laravel Comments')
    ->call('nextStep')
    ->emitEvents()->in($wizard);

$checkoutState = $wizard->getStepState('checkout-step');

Livewire::test(CheckoutStep::class, $checkoutState)
    ->call('placeOrder');

$this->assertDatabaseHas(Order::class, [...]);
```

This example illustrates how you can use `getStepState`. It will fetch the
global state from the wizard so you can pass it on to your step's test. It
makes it easy to work with real data and test your wizard completely.
