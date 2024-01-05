<?php

namespace Narolalabs\ErrorLens\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Narolalabs\ErrorLens\Models\ErrorLog;
use Narolalabs\ErrorLens\Models\ErrorLogConfig;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class ErrorLens
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        // Store configuration in cache
        if (Cache::get('error-lens')) {
            $errorLogConfigs = collect($this->flattenArray(Cache::get('error-lens')));
        }
        else {
            // If configuration data is not in the cache, then pick from database
            $errorLogConfigs = ErrorLogConfig::pluck('value', 'key');
            Cache::put('error-lens', $errorLogConfigs->toArray(), now()->addMinutes(10));
        }
        
        // Modify the key name of the config
        $errorLogConfigs = $errorLogConfigs->mapWithKeys(function ($value, $key) {
            return ['error-lens.' . $key => $value];
        })->toArray();

        // Update the configuration value
        config($errorLogConfigs);

        $response = $next($request);
        $exception = $response->exception;
        if (config('app.env') == 'production' && !config('app.debug')) {
            if ($exception) {
                try {
                    $guards = array_keys(config('auth.guards')) ;
                    
                    $guardName = null;
                    foreach($guards as $guard){
                        if(auth()->guard($guard)->check()){
                            $guardName = $guard;
                        }
                    }

                    // Replace the confidential string with stars (*)
                    $confidetialFields = explode(',', config('error-lens.security.confidentialFieldNames'));
                    $requestedData = collect($request->all())->map(function ($value, $key) use ($confidetialFields) {
                        return in_array($key, $confidetialFields) ? Str::padRight('', strlen($value), '*') : $value;
                    });

                    $error = [
                        [
                            'message' => $exception->getMessage(),
                            'file' => $exception->getFile(),
                            'line' => $exception->getLine(),
                            'code' => ($exception->getCode() !== 0) ? $exception->getCode() : 500,
                            // 'previous' => $exception->getPrevious(),
                        ],
                    ];

                    $error = collect(array_merge($exception->getTrace(), $error))->filter(function ($files) {
                        if ( isset($files['file']) && !Str::contains($files['file'], 'vendor') &&
                            !Str::contains($files['file'], 'Middleware\ErrorLens.php') &&
                            !Str::contains($files['file'], 'public\index.php') &&
                            !Str::contains($files['file'], 'server.php') &&
                            !Str::contains($files['file'], 'index.php')
                        ) {
                            return $files;
                        }
                    })->values()->all();

                    $browser = Agent::browser();

                    $message = !empty($exception->getMessage()) ?
                        $exception->getMessage()
                        : $exception->getStatusCode() . ' | Not found - ' . $request->fullUrl();

                    ErrorLog::create([
                        'url' => $request->url(),
                        'request_data' => config('error-lens.security.storeRequestedData') == '1' ? $requestedData->all() : null,
                        'headers' => request()->header(),
                        'message' => $message,
                        'error' => $error,
                        'trace' => $exception->getTrace(),
                        'email' => $request->user() ? $request->user()->email : null,
                        'ip_address' => $request->ip(),
                        'previous_url' => url()->previous(),
                        'browser' => "$browser - v"  . Agent::version($browser),
                        'guard' => $guardName
                    ]);
                } catch (\Exception $e) {
                }
            }
        }

        return $response;
    }

    /**
     * Make array to dot flatten
     *
     * @param [type] $array
     * @param string $prefix
     * @return void
     */
    private function flattenArray(array $array, string $prefix = '')
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = $prefix . $key;

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenArray($value, $newKey . '.'));
            } else {
                $result[$newKey] = $value;
            }
        }

        return $result;
    }

}
