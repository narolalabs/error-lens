<?php

namespace Narolalabs\ErrorLens\Middleware;

use Closure;
use Narolalabs\ErrorLens\Services\ConfigurationService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;

class HttpBasicAuth
{
    private $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    public function handle($request, Closure $next)
    {
        // Store configuration in cache
        if (Cache::get('authenticationDetails')) {
            $validCredentials = Cache::get('authenticationDetails');
        } else {
            // If configuration data is not in the cache, then pick from database
            $validCredentials = $this->configurationService->fetchConfigurationsDetail(['authenticate.username', 'authenticate.password']);
            Cache::put('authenticationDetails', $validCredentials, now()->addWeek(1));
        }

        $user = $request->getUser();
        $password = $request->getPassword();

        // Only authenticate if user set the username and password 
        // using the command "php artisan error-lens:authentication"
        if (
            (
                isset($validCredentials['authenticate.username']) &&
                isset($validCredentials['authenticate.password'])
            ) && (
                $validCredentials['authenticate.username'] != $user ||
                !Hash::check($password, $validCredentials['authenticate.password'])
            )
        ) {
            $headers = ['WWW-Authenticate' => 'Basic'];
            return response('Unauthorized', 401, $headers);
        }

        return $next($request);
    }
}