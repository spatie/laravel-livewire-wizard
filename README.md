
[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/support-ukraine.svg?t=1" />](https://supportukrainenow.org)

# Build wizards using Livewire

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/laravel-livewire-wizard.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-livewire-wizard)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-livewire-wizard/run-tests?label=tests)](https://github.com/spatie/laravel-livewire-wizard/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/spatie/laravel-livewire-wizard/Check%20&%20fix%20styling?label=code%20style)](https://github.com/spatie/laravel-livewire-wizard/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/laravel-livewire-wizard.svg?style=flat-square)](https://packagist.org/packages/spatie/laravel-livewire-wizard)

This package offers lightweight Livewire components that allow you to easily build a wizard. With "wizard" we mean a multi-step process in which each step has its own screen.

![screenshot](https://github.com/spatie/laravel-livewire-wizard/blob/main/docs/images/screenshot.png?raw=true)

Here's what a wizard component class could look like.

```php
use Spatie\LivewireWizard\Components\WizardComponent

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

You can easily [control which step is displayed](https://spatie.be/docs/laravel-livewire-wizard/v1/usage/navigating-steps), [access state of other steps](https://spatie.be/docs/laravel-livewire-wizard/v1/usage/accessing-state), and [build any navigation](https://spatie.be/docs/laravel-livewire-wizard/v1/usage/rendering-navigation) you desire.

In [this repo on GitHub](https://github.com/spatie/laravel-livewire-wizard-demo-app), you'll find a demo Laravel application that uses the laravel-livewire-wizard package to create a simple checkout flow.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-livewire-wizard.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-livewire-wizard)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Documentation

All documentation is available [on our documentation site](https://spatie.be/docs/laravel-livewire-wizard).

## Testing

```bash
composer test
```

## Alternatives

Our package is headless, meaning it does not provide UI, but it offers functions to easily build any UI you want. If you do not wish to build your own UI, you could consider using [vildanbina/livewire-wizard](https://github.com/vildanbina/livewire-wizard), which  includes pre-built navigation and CSS.

[Filament](https://filamentphp.com) users could also take a look at [the built-in wizard functionality](https://filamentphp.com/docs/2.x/forms/layout#wizard).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [Rias Van der Veken](https://github.com/riasvdv)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
