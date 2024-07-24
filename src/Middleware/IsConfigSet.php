<?php

namespace Narolalabs\ErrorLens\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Narolalabs\ErrorLens\Services\ConfigurationService;

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
            'error_preferences.severityLevel',
            'error_preferences.enableDisableErrorTracking'
        ];

        // Load the set configurations
        $this->configurationService->getConfigurations();

        // Fetch the required configs from database.
        $configs = $this->configurationService->fetchConfigurationsDetail($requiredConfigs);

        // If required configs not set then navigate to the config page with error 
        // to ask setup the configurations
        if (count($requiredConfigs) != $configs->count()) {
            if (!isset($configs['error_preferences.severityLevel'])) {
                Session::flash('error-lens-error', 'Please configure the severity level required for tracking error logs.');
                return redirect()->route('error-lens.config');
            }
            Session::flash('error-lens-error', "Please set up the required configuration before initializing the tracking of error logs.");
            return redirect()->route('error-lens.config');
        }

        if ($configs['error_preferences.enableDisableErrorTracking'] == '0') {
            Session::flash('error-lens-error', "You have disabled error tracking. Please enable it from config to continue tracking errors.");
        }

        // Check that recommended settings are set in environment or not.
        // if (config('error-lens.error_preferences.haventProductionEnv') == 0 && 
        //     strtolower(config('app.env') ?? '') !== 'production'
        // ) {
        //     Session::flash('error-lens-error', "Set your system environment to PRODUCTION to track error logs, or configure the relevant environment settings accordingly");
        // }
        // else if (
        //     config('error-lens.error_preferences.haventProductionEnv') == 1 && 
        //     strtolower(config('app.env') ?? '') !== config('error-lens.error_preferences.customEnvName')
        // ) {
        //     Session::flash('error-lens-error', 'Set your system environment to "'.config('error-lens.error_preferences.customEnvName').'" to track error logs.');
        // }
        // else if (config('app.debug') !== false) {
        //     Session::flash('error-lens-error', "Set your debug environment to FALSE to track error logs.");
        // }

        return $next($request);
    }
}