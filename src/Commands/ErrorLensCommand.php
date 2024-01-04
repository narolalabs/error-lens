<?php

namespace Narolalabs\ErrorLens\Commands;

use Illuminate\Console\Command;

class ErrorLensCommand extends Command
{
    public $signature = 'error-lens';

    public $description = 'My command';

    public function handle(): int
    {
        // Ask the username and password before installation
        $this->call('error-lens:authentication');

        // Success message
        $this->info('The package has been installed successfully.');

        return self::SUCCESS;
    }
}
