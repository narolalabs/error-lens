<?php

namespace Narolalabs\ErrorLens\Middleware;

use Closure;
use Narolalabs\ErrorLens\Services\ConfigurationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class IsConfigSet
{
    private $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    public function handle($request, Closure $next)
    {
        $requiredConfigs = [
            'error_preferences.severityLevel'
        ];

        // Fetch the required configs from database.
        $configs = $this->configurationService->fetchConfigurationsDetail($requiredConfigs);

        // If required configs not set then navigate to the config page with error 
        // to ask setup the configurations
        if (count($requiredConfigs) != $configs->count()) {
            if ( ! isset($configs['error_preferences.severityLevel'])) {
                return redirect()->route('error-lens.config')->withError('Please configure the severity level required for tracking error logs.');    
            }
            return redirect()->route('error-lens.config')->withError('Please set up the required configuration before initializing the tracking of error logs.');
        }
        

        return $next($request);
    }
}