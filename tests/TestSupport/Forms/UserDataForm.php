<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Forms;

use Livewire\Form;
use Spatie\LivewireWizard\Tests\TestSupport\Enums\LikesCoffeeEnum;

class UserDataForm extends Form
{
    public string $name = '';
    public string $email = '';
    public ?LikesCoffeeEnum  $likes_coffee;
}
