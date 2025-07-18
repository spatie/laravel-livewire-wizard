# Collection of spatie/livewire-wizard code


## Dehydrator

```php
<?php  
  
declare(strict_types=1);  
  
namespace Modules\Complaint\Utils;  
  
use Livewire\Component;  
use Livewire\Mechanisms\HandleComponents\ComponentContext;  
use Livewire\Mechanisms\HandleComponents\HandleComponents;  
  
class ComponentHydrator extends HandleComponents  
{  
    public function __construct()  
    {  
        $this->propertySynthesizers = app(HandleComponents::class)->propertySynthesizers;  
    }  
  
    public function dehydrateData(Component $component, mixed $data): array  
    {  
        $context = new ComponentContext($component);  
        $data = $this->dehydrate($data, $context, '');  
  
        return $data;  
    }  
  
    public function hydrateData(Component $component, array $data): array  
    {  
        $context = new ComponentContext($component);  
        $data = $this->hydrateDataProperties($data, $context);  
  
        return $data;  
    }  
  
    // see hydrateProperties in HandleComponents for reference  
    protected function hydrateDataProperties(array $data, ComponentContext $context): array  
    {  
        $result = [];  
        foreach ($data as $key => $value) {  
            $child = $this->hydrate($value, $context, $key);  
  
            $result[$key] = $child;  
        }  
  
        return $result;  
    }  
}
```

## Dehydrated events

```php
<?php  
  
declare(strict_types=1);  
  
namespace Modules\Complaint\Concerns\Livewire;  
  
use Modules\Complaint\Utils\ComponentHydrator;  
  
trait DehydratedStateEvents  
{  
    public function dispatchDehydrated($event, ...$params)  
    {  
        $hydrator = (new ComponentHydrator);  
        $newParams = collect($params)->map(fn ($param) => $hydrator->dehydrateData($this, $param))->toArray();  
  
        return parent::dispatch($event, ...$newParams);  
    }  
  
    public function previousStep()  
    {  
        $this->dispatchDehydrated('previousStep', $this->state()->currentStep())->to($this->wizardClassName);  
    }  
  
    public function nextStep()  
    {  
        $this->dispatchDehydrated('nextStep', $this->state()->currentStep())->to($this->wizardClassName);  
    }  
  
    public function showStep(string $stepName)  
    {  
        $this->dispatchDehydrated('showStep', toStepName: $stepName, currentStepState: $this->state()->currentStep())->to($this->wizardClassName);  
    }  
}
```
## Changes in base wizard

```php
$parsedValues = (new ComponentHydrator)->hydrateData($this, $currentStepState);  
parent::showStep($toStepName, $parsedValues);
```