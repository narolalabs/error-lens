<?php

namespace Narolalabs\ErrorLens\Commands;

use Illuminate\Console\Command;
use Narolalabs\ErrorLens\Services\ConfigurationService;
use Illuminate\Support\Facades\Cache;

class AuthCommand extends Command
{
    private $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        parent::__construct();

        $this->configurationService = $configurationService;
    }

    public $signature = 'error-lens:authentication';

    public $description = 'Set or update authentication detail to prevent to unauthorized access';

    public function handle(): int
    {
        // Information statement
        $this->comment('To ensure security and restrict unauthorized entry, kindly enter the username and password, enabling you to authenticate and access the system using the provided credentials.');

        // Forcefully asked user to provide username and password
        // do {
            list($username, $password, $confirmation) = $this->askUsernamePassword();
        // } while (empty($username) || empty($password));

        if (!empty($username) && !empty($password)) {
            $storeCredentials = $this->configurationService->updateAuthenticationDetail(trim($username), trim($password));
            if ($storeCredentials[0]) {
                // clear cache
                $this->call('cache:clear');
                Cache::forget('error-lens');
    
                // Information statement
                $this->info('ErrorLens authorization credentials have been set successfully.');
    
                return self::SUCCESS;
            }
        }
        else if(!$confirmation) {
            return false;
        }
        else if (empty($username) || empty($password)) {
            $this->error('Please provide non-empty username and password.');
            return false;
        }
        else {
            $this->error('Something went wrong. Please try after some time.');
            return false;
        }
    }

    public function askUsernamePassword()
    {
        // Ask username and password during installtion
        $username = $this->ask('Your username');
        $password = $this->ask('Security password');

        // If empty then give message
        if (empty($username) || empty($password)) {
            $this->comment('To proceed, please provide both your username and password. Without this information, the process cannot be completed. Kindly try again.');
        }

        if (!empty($username) && !empty($password)) {
            // Show detail to confirm it
            $this->table(
                ['Username', 'Password'],
                [[$username, $password]]
            );

            // Ask for confirmation that provided credentials are correct or not
            $confirmation = $this->confirm('Are you sure want to confirm it?');
            if (!$confirmation) {
                $username = $password = '';
            }

            // If credentials are correct then return to store
            return [$username, $password, $confirmation];
        }

        return ['', '', false];
        
    }
}
