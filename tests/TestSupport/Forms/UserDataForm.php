<?php

namespace Spatie\LivewireWizard\Tests\TestSupport\Forms;

use Livewire\Form;

class UserDataForm extends Form
{
    public string $name = '';
    public string $email = '';
    public ?LikesCofeeEnum  $likes_cofee;
}
