<div align="left">
<h1>Build wizards using Livewire</h1>
</div>

This package is a fork of [Spaties larave-livewire-wizard package](https://github.com/spatie/laravel-livewire-wizard), and tries to address and issue I ran into while developing using the package. Should you have encountered the same issue, migrating to this package should be swift, and a near drop in replacement.

## The issue
I like what Livewire offers, Forms, Models and Enums all easily accessable in Livewire/Components. Unfortunately using these in a StepComponent, will break the state management done by the Wizard. Navigating back to a previous step that uses on of these data type will likely throw an Exception.

```php
  Cannot assign string to property \StepComponent::$likes_coffee of type \Enums\LikesCoffeeEnum;
```

This is caused because these object don't retain type while traversing the Livewire event system. By default, events get serialized to json, and deserialized by the wizard again. This change uses Livewire/Synthesizers and existing Livewire code to process these events instead. The end result, Forms, Models, Enums and all other Synthesizable data types available in StepComponents.

## Documentation

### Migrating to this package
This package ships with two new classes the replace the old ones. 
```diff
- use Spatie\LivewireWizard\Components\WizardComponent;
+ use Spatie\LivewireWizard\Components\HydratedWizardComponent;

- class MyWizardComponent extends WizardComponent
+ class MyWizardComponent extends HydratedWizardComponent
{

}
```

```diff
- use Spatie\LivewireWizard\Components\StepComponent;
+ use Spatie\LivewireWizard\Components\DehydratedStepComponent;

- class MyStepComponent extends StepComponent
+ class MyStepComponent extends DehydratedStepComponent
{

}
```
Changing these classes is all it takes for your steps and wizard to support a wider variety of datatypes. For backwards compatibility reasons, the original components are still available. StepComponents that don't require Forms or Models can still extend `Spatie\LivewireWizard\Components\StepComponent`, skipping the dehydration of events. This is compatible with the new `HydratedWizardComponent`, so mixed `StepComponent` classes is allowed.

### Alternative method
The changes made for this new feature are also available as traits. Adding these to existing Wizards and Steps should have the same result.  

See, `Spatie\LivewireWizard\Components\Concerns\WizardHydratesState` and `Spatie\LivewireWizard\Components\Concerns\DehydratedStepComponent` for more info.

```diff
use Spatie\LivewireWizard\Components\WizardComponent;
+ use Spatie\LivewireWizard\Components\Concerns\WizardHydratesState;

class MyWizardComponent extends WizardComponent
{
+   use WizardHydratesState;

}
```

```diff
  use Spatie\LivewireWizard\Components\StepComponent;
+ use Spatie\LivewireWizard\Components\Concerns\DehydratedStepComponent;

class MyStepComponent extends StepComponent
{
+    use DehydratedStepComponent;

}
```


## Testing

```bash
composer test
```

## Credits
Credits to the original developers
- [Freek Van der Herten](https://github.com/freekmurze)
- [Rias Van der Veken](https://github.com/riasvdv)
- [All Contributors](https://github.com/spatie/laravel-livewire-wizard/graphs/contributors)

And credits to the developers over at [Dolphiq](https://dolphiq.nl/), for providing feedback on this.

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
