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

When the next step is loaded, its state gets passed. The `StepComponent` will
process it and make it available for you to display.

Testing your wizard needs some magic. Because well, wizards do magic.

## Navigating your wizard

`emitEvents` allows you to take all events from a `StepComponent` and can make
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

`emitEvents` takes the event thats emitted by `nextStep` and passes it to the
wizard. The wizard processes it and takes you to the next step.

## Testing state in a StepComponent

Now you know how to navigate your wizard in your tests, let's talk state. 

We have a cart wizard. The initial state of this cart is a list of items, made
available to the `show-cart-step`. We want to be sure this step has access to
those items. The cart has only one item, Spatie's Laravel Comments.

`getStateForStep` allows you to get state for a step from a wizard. The example
below shows how you can test if the cart contains the item you expect.

```php
$wizard = Livewire::test(CartWizard::class, [
    'show-cart-step' => [
        'items' => [
            0 => [
                'detail' => 'Laravel Comments'
            ],
        ],
    ],
]);

$showCartState = $wizard->getStateForStep('show-cart-step');

Livewire::test(ShowCartStep::class, $showCartState)
    ->assertSet('items.0.detail', 'Laravel Comments');
```