<?php

namespace Narolalabs\ErrorLens\Exceptions;

use \App\Exceptions\Handler;
use Throwable;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Narolalabs\ErrorLens\Models\ErrorLog;
use Narolalabs\ErrorLens\Models\ErrorLogConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ErrorLensHandler extends Handler
{
    public function render($request, $exception)
    {
        try {
            $errorLogConfigs = $this->getConfigurations();

            $exceptionStatusCode = $this->getStatusCode($exception);

            $trackErrorOrNot = $this->trackErrorOrNot($exceptionStatusCode, $errorLogConfigs);

            // Log errors when the environment is production, debug mode is set to false, and error tracking is configured.
            if ($this->isValidEnvironment() && !config('app.debug') && $trackErrorOrNot) {
                if ($exception) {
                    $guardName = $this->getGuardName();

                    // Replace the confidential string with stars (*)
                    $requestedData = $this->maskRequestedData($request);

                    $transformData = $this->transformErrorData($request, $exception, $exceptionStatusCode);

                    ErrorLog::create([
                        'method' => $request->getMethod(),
                        'url' => $request->url(),
                        'request_data' => config('error-lens.security.storeRequestedData') == '1' ? $requestedData->all() : null,
                        'headers' => $transformData['headers'],
                        'message' => $transformData['message'],
                        'error' => $transformData['error'],
                        'trace' => $transformData['trace'],
                        'email' => $this->getUserEmail($guardName),
                        'ip_address' => $request->ip(),
                        'previous_url' => url()->previous(),
                        'browser' => $transformData['browser'] . " - v" . Agent::version($transformData['browser']),
                        'guard' => $guardName
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // dd($e);
        }

        return parent::render($request, $exception);
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

    /**
     * Remove the sensitive header information from the header
     *
     * @param array $headers
     * @return array
     */
    private function removeSensitiveHeaderInfo(array $headers): array
    {
        // List of sensitive headers to remove
        $sensitiveHeaders = ['php-auth-user', 'php-auth-pw', 'Authorization', 'Cookie', 'X-CSRF-Token', 'User-Agent', 'Referer'];

        // Remove sensitive headers
        foreach ($sensitiveHeaders as $header) {
            if (isset ($headers[$header])) {
                unset($headers[$header]);
            }
        }

        return $headers;
    }

    private function maskRequestedData(Request $request)
    {
        // Replace the confidential string with stars (*)
        $confidentialFields = explode(',', config('error-lens.security.confidentialFieldNames'));
        $confidentialFields = array_merge($confidentialFields, config('masked-keywords'));
        $requestedData = collect($request->all())->map(function ($value, $key) use ($confidentialFields) {
            return in_array($key, $confidentialFields) ? Str::padRight('', strlen($value), '*') : $value;
        });
        return $requestedData;
    }

    private function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function getConfigurations()
    {
        // Store configuration in cache
        if (Cache::get('error-lens')) {
            $errorLogConfigs = collect($this->flattenArray(Cache::get('error-lens')));
        } else {
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

        return $errorLogConfigs;
    }

    private function getStatusCode($exception)
    {
        if ($exception) {
            if (method_exists($exception, 'getStatusCode')) {
                return $exception->getStatusCode();
            } else {
                return ($exception->getCode() !== 0) ? $exception->getCode() : 500;
            }
        }
        return null;
    }

    private function trackErrorOrNot($exceptionStatusCode, $errorLogConfigs)
    {
        $trackErrorOrNot = false;
        if ($exceptionStatusCode && isset ($errorLogConfigs['error-lens.error_preferences.severityLevel'])) {
            // Track whether a severity level is set for error tracking.
            $configSeverityLevel = array_map('trim', explode(',', $errorLogConfigs['error-lens.error_preferences.severityLevel']));
            $trackErrorOrNot = in_array(substr($exceptionStatusCode, 0, 1) . 'xx', $configSeverityLevel);

            if (
                $trackErrorOrNot &&
                isset ($errorLogConfigs['error-lens.error_preferences.severityLevel']) &&
                isset ($errorLogConfigs['error-lens.error_preferences.skipErrorCodes'])
            ) {
                // If severity is set but the error code is added to the skip error code list, then it should be ignored.
                $skipErrorCodes = array_map('trim', explode(',', $errorLogConfigs['error-lens.error_preferences.skipErrorCodes']));
                $trackErrorOrNot = !in_array($exceptionStatusCode, $skipErrorCodes);
            }
        }
        return $trackErrorOrNot;
    }

    private function isValidEnvironment()
    {
        if (config('error-lens.error_preferences.haventProductionEnv') == 1) {
            return strtolower(config('app.env') ?? '') == config('error-lens.error_preferences.customEnvName');
        }
        return strtolower(config('app.env') ?? '') == 'production';
        ;
    }

    private function getGuardName()
    {
        // Get all the guard name which are in the system
        $guards = array_keys(config('auth.guards'));
        try {
            // Set the logged-in guard name
            foreach ($guards as $guard) {
                if (auth()->guard($guard)->check()) {
                    return $guard;
                }
            }
            return null;
        } catch (\Throwable $e) {
            return null;
        }

    }

    private function transformErrorData($request, $exception, $exceptionStatusCode)
    {
        $error = [
            [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exceptionStatusCode,
                'previous' => $exception->getPrevious(),
            ],
        ];

        $response['error'] = collect(array_merge($exception->getTrace(), $error))->filter(function ($files) {
            if (
                isset ($files['file']) && !Str::contains($files['file'], 'vendor') &&
                !Str::contains($files['file'], 'Middleware\ErrorLens.php') &&
                !Str::contains($files['file'], 'public\index.php') &&
                !Str::contains($files['file'], 'server.php') &&
                !Str::contains($files['file'], 'index.php')
            ) {
                return $files;
            }
        })->values()->all();

        $response['browser'] = Agent::browser();

        $response['message'] = !empty ($exception->getMessage()) ?
            $exception->getMessage()
            : $exception->getStatusCode() . ' | Not found - ' . $request->fullUrl();

        $response['headers'] = $this->removeSensitiveHeaderInfo(request()->header());
        $response['trace'] = $this->isJson(json_encode($exception->getTrace())) ? $exception->getTrace() : ['trace' => $exception->getTraceAsString()];

        return $response;
    }
    
    private function getUserEmail($guardName)
    {
        try {
            return $guardName && auth()->guard($guardName)->check() ? auth()->guard($guardName)->user()->email : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}