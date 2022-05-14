<?php

namespace Spatie\LivewireWizard;

use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Livewire;
use Spatie\LivewireWizard\Exceptions\InvalidStepComponent;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Exceptions\NoStepsReturned;

abstract class WizardComponent extends Component
{
    public array $allStepState = [];
    public string $currentStepName;

    protected $listeners = [
        'previousStep',
        'nextStep',
        'activateStep',
    ];

    /** @return <int, class-string<StepComponent> */
    abstract public function steps(): array;

    public function mount()
    {
        $this->currentStepName = $this->stepNames()->first();
    }

    public function stepNames(): Collection
    {
        $steps = collect($this->steps())
            ->each(function (string $stepClassName) {
                if (! is_a($stepClassName, StepComponent::class, true)) {
                    throw InvalidStepComponent::doesNotExtendStepComponent(static::class, $stepClassName);
                }
            })
            ->map(function (string $stepClassName) {
                $alias = Livewire::getAlias($stepClassName);

                if (is_null($alias)) {
                    throw InvalidStepComponent::notRegisteredWithLivewire(static::class, $stepClassName);
                }

                return $alias;
            });

        if ($steps->isEmpty()) {
            throw NoStepsReturned::make(static::class);
        }

        return $steps;
    }

    public function previousStep(array $currentStepState)
    {
        $previousStep = collect($this->stepNames())
            ->before(fn (string $step) => $step === $this->currentStepName);

        if (! $previousStep) {
            throw NoPreviousStep::make(self::class, $this->currentStepName);
        }

        $this->activateStep($previousStep, $currentStepState);
    }

    public function nextStep(array $currentStepState)
    {
        $nextStep = collect($this->stepNames())
            ->after(fn (string $step) => $step === $this->currentStepName);

        if (! $nextStep) {
            throw NoNextStep::make(self::class, $this->currentStepName);
        }

        $this->activateStep($nextStep, $currentStepState);
    }

    public function activateStep($toStepName, array $currentStepState)
    {
        $this->allStepState[$this->currentStepName] = $currentStepState;

        $this->currentStepName = $toStepName;
    }

    public function render()
    {
        $currentStepState = array_merge(
            $this->allStepState[$this->currentStepName] ?? [],
            ['allStepsState' => $this->allStepState],
        );

        return view('livewire-wizard::wizard', compact('currentStepState'));
    }
}
