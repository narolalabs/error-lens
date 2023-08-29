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
                            'previous' => $exception->getPrevious(),
                        ],
                    ];
                    
                    $errorTraces = collect($exception->getTrace())->filter(function ($files) {
                        if ( isset($files['file']) && !Str::contains($files['file'], 'vendor') &&
                            !Str::contains($files['file'], 'Middleware\ErrorLens.php') &&
                            !Str::contains($files['file'], 'public\index.php') &&
                            !Str::contains($files['file'], 'index.php')
                        ) {
                            return $files;
                        }
                    })->values()->all();

                    $error = array_merge($error, $errorTraces);

                    $browser = Agent::browser();
                    ErrorLog::create([
                        'url' => $request->url(),
                        'query_string' => $request->query(),
                        'message' => $exception->getMessage(),
                        'error' => $error,
                        'trace' => $exception->getTrace(),
                        'email' => $request->user() ? $request->user()->email : null,
                        'ip_address' => $request->ip(),
                        'previous_url' => url()->previous(),
                        'browser' => "$browser - "  . Agent::version($browser),
                    ]);
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }
            }
        }

        return $response;
    }
}
