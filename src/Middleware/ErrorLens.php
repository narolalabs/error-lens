<?php

namespace Narolalabs\ErrorLens\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;
use Narolalabs\ErrorLens\Models\ErrorLog;
use Symfony\Component\HttpFoundation\Response;

class ErrorLens
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $exception = $response->exception;
        if ( config('app.env') == 'production' && !config('app.debug') ) {
            if ( $exception ) {
                try {
                    $error = [
                        [
                            'message' => $exception->getMessage(),
                            'file' => $exception->getFile(),
                            'line' => $exception->getLine(),
                            'code' => $exception->getCode(),
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
                        'request_data' => $request->all(),
                        'headers' => request()->header(),
                        'message' => $message,
                        'error' => $error,
                        'trace' => $exception->getTrace(),
                        'email' => $request->user() ? $request->user()->email : null,
                        'ip_address' => $request->ip(),
                        'previous_url' => url()->previous(),
                        'browser' => "$browser - v"  . Agent::version($browser),
                    ]);
                } catch (\Exception $e) {
                }
            }
        }

        return $response;
    }
}
