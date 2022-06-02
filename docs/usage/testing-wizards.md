---
title: Testing wizards
weight: 6
---

Livewire has an extensive set of testing utilities; we offer a few more to 
test your wizards.

## Internals

Let's briefly touch on some of the internals.

A wizard keeps track of its steps and state. To make your wizard easy to 
navigate, an event is emitted to the wizard when you call `nextStep` or
`previousStep`. This event will also contain the state of the step it is called
from.

The wizard will receive the event and shows you the page you requested, along
with the state for that step. If you're familiar with Livewire, the wizard
renders a component like this:

```blade
@livewire('first-step', $state, 'unique-key')
```

As a wizard and its steps are tightly coupled, it's not always useful to test
individual components. They don't paint the full picture.

Testing your wizard needs some magic. Because well, wizards do magic.

## Navigating your wizard

Navigating your wizard is done with events. The browser uses Javascript to
pass them to the respective component. This means you either need to use Dusk
to navigate, or use a method called `emitEvents`.

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

State is stored in the wizard and each step by its own has no idea or access to
it. In a browser, state is passed to steps automatically, but in your tests you
need `getStepState` to fetch the state from the wizard and pass it to a step.

We go back to our cart wizard where we want to test state. Our example is a 
simple one, but this can be used to test your final step where you're 
processing your cart.

We're going to emulate ordering Spatie's Laravel Comments. `initialState` is 
used to populate the cart. We're not implementing the address step here and
go straight to checkout.

```php
$initialState = 'show-cart-step' => [
    'items' => [
        0 => [
            'detail' => 'Laravel Comments'
            'quantity' => 1,
        ],
    ],
],

$wizard = Livewire::test(CartWizard::class, [
    'initialState' => $initialState,  
]);

$showCartState = $wizard->getStepState('show-cart-step');

Livewire::test(ShowCartStep::class, $showCartState)
    ->assertSet('items.0.detail', 'Laravel Comments')
    ->call('items.0.quantity', 5)
    ->call('nextStep')
    ->emitEvents()->in($wizard);

$checkoutState = $wizard->getStepState('checkout-step');

Livewire::test(CheckoutStep::class, $checkoutState)
    ->call('placeOrder');

$this->assertDatabaseHas(Order::class, [...]);
```

We start by creating some dummy data for our cart. We've added the Laravel
Comments package to it. The customer decides they more licenses for their team,
and increases the quantity to 5.

In the `CheckoutStep` the user clicks to place the order and we want to assert
that this logic works as expected. This means we need access to all state.

`getStepState` gets all state relevant for that step. It will make sure the
step component behaves exactly the same as in a browser. It also includes the
global state, which you happen to need in the checkout.

Once the user clicks `placeOrder`, it fetches state from the first step and
creates the order. We then assert that the database has an order and things
are working as expected.
