<?php

namespace Narolalabs\ErrorLens\Exceptions;

use \Illuminate\Foundation\Exceptions\Handler;
use Throwable;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Narolalabs\ErrorLens\Models\ErrorLog;
use Narolalabs\ErrorLens\Models\ErrorLogConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ErrorLensHandler extends Handler
{
    private $defaultSkipErrorCodes = [400, 401, 403, 404, 406, 409, 413, 422];
    private $storeBeforeAfterErrorLines = 10;

    public function render($request, $exception)
    {
        try {
            $currentUrl = $request->url();

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

                    $stackDetail = $this->getStackDetail(collect($transformData['error']), $exception);

                    $existingData = [
                        'method' => $request->getMethod(),
                        'url' => $request->url(),
                        'status' => $exceptionStatusCode,
                        'message' => $transformData['message'],
                        'error_file' => isset($transformData['errorData']['file']) ? $transformData['errorData']['file'] : null,
                        'error_line' => isset($transformData['errorData']['line']) ? $transformData['errorData']['line'] : null,
                        'stack' => $stackDetail['stack'],
                        'stack_start' => $stackDetail['stack_start'],
                        'stack_end' => $stackDetail['stack_end'],
                        'email' => $this->getUserEmail($guardName),
                        'ip_address' => $request->ip(),
                        'previous_url' => url()->previous(),
                        'browser' => $transformData['browser'] . " - v" . Agent::version($transformData['browser']),
                        'guard' => $guardName
                    ];

                    $cacheKey = 'error_log_' . md5(json_encode($existingData));

                    // Try to get the error log from cache
                    $errorExist = Cache::remember($cacheKey, 180, function () use ($existingData) {
                        return ErrorLog::where($existingData)->first();
                    });

                    $errorLog = ErrorLog::create([
                        'method' => $request->getMethod(),
                        'url' => $request->url(),
                        'status' => $exceptionStatusCode,
                        'request_data' => config('error-lens.security.storeRequestedData') == '1' ? $requestedData->all() : null,
                        'headers' => $transformData['headers'],
                        'message' => $transformData['message'],
                        'error_file' => isset($transformData['errorData']['file']) ? $transformData['errorData']['file'] : null,
                        'error_line' => $stackDetail['line'],
                        'error' => $transformData['error'],
                        'trace' => $transformData['trace'],
                        'stack' => $stackDetail['stack'],
                        'stack_start' => $stackDetail['stack_start'],
                        'stack_end' => $stackDetail['stack_end'],
                        'email' => $this->getUserEmail($guardName),
                        'ip_address' => $request->ip(),
                        'previous_url' => url()->previous(),
                        'browser' => $transformData['browser'] . " - v" . Agent::version($transformData['browser']),
                        'guard' => $guardName,
                        'repeated' => ($errorExist) ? $errorExist->id : null
                    ]);

                    if (str_contains($currentUrl, '/error-lens')) {
                        $errorDetail = collect($transformData['error'])->first();
                        $data['errorLog'] = $errorLog;
                        $data['stack'] = implode('', file($exception->getFile()));
                        $data['line'] = $exception->getLine();
                        $data['stack_start'] = $stackDetail['stack_start'];
                        $data['stack_end'] = $stackDetail['stack_end'];

                        $data['errorFile'] = (($errorDetail && isset($errorDetail['file']))) ? $errorDetail['file'] : '';
                        $data['errorCode'] = (($errorDetail && isset($errorDetail['code']))) ? $errorDetail['code'] : '';
                        
                        return response()->view(
                            'error-lens::system-error.error-detail',
                            $data,
                            200
                        );
                    }
                }
            }
        } catch (\Throwable $e) {
            // dd($e);
        }

        return parent::render($request, $exception);
    }

    private function extractUrls($string)
    {
        // Regular expression to match URLs or file paths
        $pattern = '/[A-Za-z]:\\\\[^\s)]+/i';
        preg_match_all($pattern, $string, $matches);
        return collect($matches[0]);
    }

    private function getStackDetail($errorCollection, $exception)
    {
        // $storeBeforeAfterErrorLines = 10;
        $fileContent = '';
        $error = $errorCollection->last();
        $file = $exception->getFile() ?? ($error && $error['file'] ? $error['file'] : '');
        $line = $exception->getLine() ?? ($error && $error['line'] ? $error['line'] : '');

        $data = [];
        if ($error && $file && $line && file_exists($file)) {
            $filePath = $file;
            $errorMessage = isset($error['message']) ? $error['message'] : $exception->getMessage();

            $trace = [];
            $exceptionTrace = $exception;
            do {
                // $trace = array_merge($trace, $exceptionTrace->getTrace());
                $trace = $exceptionTrace->getTrace();
                $exceptionTrace = $exceptionTrace->getPrevious();
            } while ($exceptionTrace);

            // Pick the view from blade file instead of cache file 
            $viewLineNumber = 0;
            if (str_contains($errorMessage, 'resources\views')) {
                $viewLineNumber = collect($trace)->filter(function ($item) {
                    return isset($item['file']) && str_contains($item['file'], '\storage\framework\views');
                })->pluck('line')->first();

                if ($viewLineNumber) {
                    $filePath = $this->extractUrls($errorMessage)->first();
                    $line = $viewLineNumber;
                }
            }

            $fileContent = file($filePath);
            array_unshift($fileContent, ""); // Instead of start the indexing 0, we start it from 1
            $totalLines = count($fileContent);

            $start = ($line - $this->storeBeforeAfterErrorLines) <= 0 ? 1 : ($line - $this->storeBeforeAfterErrorLines);
            $end = ($totalLines > ($start + ($this->storeBeforeAfterErrorLines * 2))) ? ($start + ($this->storeBeforeAfterErrorLines * 2)) : $totalLines;

            // If the first line is empty then PrismJS skip that line. make the first line non-empty.
            while (isset($fileContent[$start]) && $this->containsNewline($fileContent[$start])) {
                $start++;
            }

            // Get the code from file using start and end line number
            $data['stack'] = array_slice($fileContent, $start, $end - $start, true);
        }

        return [
            'stack' => isset($data['stack']) ? implode('', $data['stack']) : null,
            'stack_start' => isset($start) ? $start : null,
            'stack_end' => isset($end) ? ($end - 1) : null,
            'line' => $line,
        ];
    }

    private function containsNewline($string)
    {
        return empty(trim($string));
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
            if (isset($headers[$header])) {
                unset($headers[$header]);
            }
        }

        return $headers;
    }

    private function maskRequestedData(Request $request)
    {
        // Replace the confidential string with stars (*)
        $confidentialFields = explode(',', config('error-lens.security.confidentialFieldNames'));
        $maskedKeyWords = config('masked-keywords') ?? [];
        $confidentialFields = array_merge($confidentialFields, $maskedKeyWords);
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
            if (isset($exception->status)) {
                return $exception->status;
            } else if (method_exists($exception, 'getStatusCode')) {
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
        if ($exceptionStatusCode && isset($errorLogConfigs['error-lens.error_preferences.severityLevel'])) {
            // Track whether a severity level is set for error tracking.
            $configSeverityLevel = array_map('trim', explode(',', $errorLogConfigs['error-lens.error_preferences.severityLevel']));
            $trackErrorOrNot = in_array(substr($exceptionStatusCode, 0, 1) . 'xx', $configSeverityLevel);

            if (
                $trackErrorOrNot &&
                isset($errorLogConfigs['error-lens.error_preferences.severityLevel']) &&
                isset($errorLogConfigs['error-lens.error_preferences.skipErrorCodes'])
            ) {
                // If severity is set but the error code is added to the skip error code list, then it should be ignored.
                $skipErrorCodes = array_map('trim', explode(',', $errorLogConfigs['error-lens.error_preferences.skipErrorCodes']));
                $skipErrorCodes = array_unique(array_merge($this->defaultSkipErrorCodes, $skipErrorCodes));
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
                isset($files['file']) && !Str::contains($files['file'], 'vendor') &&
                !Str::contains($files['file'], 'Middleware\ErrorLens.php') &&
                !Str::contains($files['file'], 'public\index.php') &&
                !Str::contains($files['file'], 'server.php') &&
                !Str::contains($files['file'], 'index.php')
            ) {
                return $files;
            }
        })->values()->all();

        $response['browser'] = Agent::browser();

        $response['message'] = !empty($exception->getMessage()) ?
            $exception->getMessage()
            : $exception->getStatusCode() . ' | Not found - ' . $request->fullUrl();

        $response['headers'] = $this->removeSensitiveHeaderInfo(request()->header());
        $response['trace'] = $this->isJson(json_encode($exception->getTrace())) ? $exception->getTrace() : ['trace' => $exception->getTraceAsString()];
        $response['errorData'] = $error[0];

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
