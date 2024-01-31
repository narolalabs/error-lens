<?php

namespace Narolalabs\ErrorLens\Middleware;

use Closure;
use Illuminate\Http\Request;
use Narolalabs\ErrorLens\Services\ErrorLogService;

class AutoRemoveErrorLogs
{
    private $errorLogService;

    public function __construct(ErrorLogService $errorLogService)
    {
        $this->errorLogService = $errorLogService;
    }
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $this->errorLogService->autoRemoveErrorLogs();
        return $next($request);
    }
}
