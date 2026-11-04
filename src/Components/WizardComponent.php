<?php

namespace Spatie\LivewireWizard\Components;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Drawer\Utils;
use Livewire\Exceptions\ComponentNotFoundException;
use Spatie\LivewireWizard\Components\Concerns\MountsWizard;
use Spatie\LivewireWizard\Exceptions\InvalidStepComponent;
use Spatie\LivewireWizard\Exceptions\NoNextStep;
use Spatie\LivewireWizard\Exceptions\NoPreviousStep;
use Spatie\LivewireWizard\Exceptions\NoStepsReturned;
use Spatie\LivewireWizard\Exceptions\StepDoesNotExist;
use Spatie\LivewireWizard\Support\ComponentHydrator;
use Spatie\LivewireWizard\Support\ResolvesLivewireComponents;
use Spatie\LivewireWizard\Support\State;

abstract class WizardComponent extends Component
{
    use MountsWizard;
    use ResolvesLivewireComponents;

    public array $allStepState = [];
    public ?string $currentStepName = null;
    public ?array $initialState = null;
    public ?string $showStep = null;

    /** @return list<string> Step classes or Livewire component names. */
    abstract public function steps(): array;

    public function initialState(): ?array
    {
        return null;
    }

    public function stepNames(): Collection
    {
        $steps = collect($this->steps())
            ->map(function (string $step) {
                try {
                    $stepClass = $this->componentClass($step);
                } catch (ComponentNotFoundException) {
                    throw class_exists($step)
                        ? InvalidStepComponent::doesNotExtendStepComponent(static::class, $step)
                        : InvalidStepComponent::notRegisteredWithLivewire(static::class, $step);
                }

                if (! is_a($stepClass, StepComponent::class, true)) {
                    throw InvalidStepComponent::doesNotExtendStepComponent(static::class, $step);
                }

                return $this->componentName($step);
            });

        if ($steps->isEmpty()) {
            throw NoStepsReturned::make(static::class);
        }

        return $steps;
    }

    #[On('previousStep')]
    public function previousStep(array $currentStepState)
    {
        $previousStep = collect($this->stepNames())
            ->before(fn (string $step) => $step === $this->currentStepName);

        if (! $previousStep) {
            throw NoPreviousStep::make(static::class, $this->currentStepName);
        }

        $this->showStep($previousStep, $currentStepState);
    }

    #[On('nextStep')]
    public function nextStep(array $currentStepState)
    {
        $nextStep = collect($this->stepNames())
            ->after(fn (string $step) => $step === $this->currentStepName);

        if (! $nextStep) {
            throw NoNextStep::make(static::class, $this->currentStepName);
        }

        $this->showStep($nextStep, $currentStepState);
    }

    #[On('showStep')]
    public function showStep($toStepName, array $currentStepState = [])
    {
        if ($this->currentStepName) {
            $state = $currentStepState;
            if (Utils::isSyntheticTuple($state)) {
                $state = app(ComponentHydrator::class)->hydrateData($this, $currentStepState);
            }

            $this->setStepState($this->currentStepName, $state);
        }

        $this->currentStepName = $toStepName;
    }

    public function setStepState(string $step, array $state = []): void
    {
        if (! $this->stepNames()->contains($step)) {
            throw StepDoesNotExist::doesNotHaveState($step);
        }

        Arr::set($this->allStepState, $step, $state);
    }

    public function getCurrentStepState(?string $step = null): array
    {
        $stepName = $step ?? $this->currentStepName;

        $stepName = class_exists($stepName)
            ? $this->componentName($stepName)
            : $stepName;

        throw_if(
            ! $this->stepNames()->contains($stepName),
            StepDoesNotExist::stepNotFound($stepName)
        );

        $allStepsState = [];

        foreach ($this->stepNames() as $name) {
            $data = Arr::get($this->allStepState, $name);

            if (is_array($data)) {
                $allStepsState[$name] = $data;
            }
        }

        return array_merge(
            Arr::get($this->allStepState, $stepName, []),
            [
                'allStepNames' => $this->stepNames()->toArray(),
                'allStepsState' => $allStepsState,
                'stateClassName' => $this->stateClass(),
                'wizardClassName' => $this->getName(),
            ],
        );
    }

    public function render()
    {
        $currentStepState = $this->getCurrentStepState();

        $view = $this->hasProvidedView()
            ? $this->getProvidedView()
            : view('livewire-wizard::wizard');

        return $view->with('currentStepState', $currentStepState);
    }

    /** @return class-string<State> */
    public function stateClass(): string
    {
        return State::class;
    }
}
