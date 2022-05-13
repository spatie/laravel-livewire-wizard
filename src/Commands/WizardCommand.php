<?php

namespace Spatie\Wizard\Commands;

use Illuminate\Console\Command;

class WizardCommand extends Command
{
    public $signature = 'laravel-livewire-wizard';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
