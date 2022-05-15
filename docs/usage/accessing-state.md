---
title: Accessing state
weight: 4
---

In any of your step components you can access the state of all other steps in the wizard. You can call any of these methods in your step component

- `allStepsState()`: returns an array containing the values of all public properties of all steps in the wizard. The key of of the items in the array is the name of the step.
- `stateForStep($stepName)`: returns the values of all public properties of the given step.
- `currentStepState`: returns an array containing the values of all public properties of the current step. The result is almost identical to Livewire's native `all()` method, but with some internal properties filtered out
