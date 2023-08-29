<?php

namespace Narolalabs\ErrorLens\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Narolalabs\ErrorLens\Models\ErrorLog;
use Illuminate\Routing\Controller;

class ErrorLogController extends Controller
{
    /**
     * Allow queryString value
     * 
     * @var array
     */
    protected static array $queryString = [
        'today', 'yesterday', 'last-month', 'current-year',
    ];

    /**
     * Default filter
     * 
     * @var string
     */
    protected static string $defaultFilter = 'today';

    /**
     * Per page record length
     * 
     * @var int
     */
    protected static int $perPageRecordLenght = 20;

    /**
     * Get default filter value
     * 
     * @return string
     */
    protected function getDefaultFilter(): string
    {
        return static::$defaultFilter;
    }

    /**
     * Get per page pagination record length
     * 
     * @return int
     */
    protected function getPerPageRecordLenght(): int
    {
        return static::$perPageRecordLenght;
    }

    /**
     * Get title of the filter
     * 
     * @param  string $queryString
     * @return string
     */
    protected function getTitle($queryString): string
    {
        return match ($queryString) {
            'today' => "Today's errors",
            'yesterday' => "Yesterday's errors",
            'last-month' => 'Last month errors',
            'current-year' => 'Current year errors',
        };
    }

    /**
     * Get filter value based on the string
     * 
     * @param  string $view
     * @return string
     */
    protected function getFilterValue($view): string
    {
        return match ($view) {
            'today' => date('Y-m-d'),
            'yesterday' => date('Y-m-d', strtotime('yesterday')),
            'last-month' => date('Y-m', strtotime('last month')),
            'current-year' => date('Y'),
        };
    }

    /**
     * For listing errors, it will redirect to Dashboard route.
     * 
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('error-lens.dashboard');
    }

    /**
     * Display dashboard view.
     * 
     * @param  Request $request
     * @return mixed
     */
    public function dashboard( Request $request ): mixed
    {
        $activeError = current(static::$queryString);

        if ( $request->view && !in_array($request->view, static::$queryString) ) {
            return redirect()->route('error-lens.index');
        }

        $data = [
            'today_errors_count' => ErrorLog::select('id')->getFilters(date('Y-m-d'))->count(),
            'yesterday_errors_count' => ErrorLog::select('id')->getFilters(date('Y-m-d', strtotime('yesterday')))->count(),
            'last_month_errors_count' => ErrorLog::select('id')->getFilters(date('Y-m', strtotime('last month')))->count(),
            'current_year_errors_count' => ErrorLog::select('id')->getFilters(date('Y'))->count(),
        ];
        $data['errorLogs'] = ErrorLog::getFilters(
            $this->getFilterValue($request->view ?? $this->getDefaultFilter())
        )
        ->select([
            'id', 'url', 'message', 'created_at',
        ])
        ->latest()
        ->paginate($this->getPerPageRecordLenght());

        $data['activeError'] = $this->getTitle($request->view ?? $this->getDefaultFilter());

        return view('error-lens::index', $data);
    }

    /**
     * View error.
     * 
     * @param  Request $request
     * @param  string  $id
     * @return mixed
     */
    public function view( Request $request, string $id ): mixed
    {
        $data['errorLog'] = ErrorLog::findOrFail($id);

        if ( $request->ajax() ) {
            $data = [
                'status' => true,
                'data' => [
                    'view' => view('error-lens::view-modal', $data)->render(),
                ],
            ];
            return response()->json($data);
        }

        return view('error-lens::view', $data);
    }

    /**
     * Clear error logs.
     * 
     * @param  Request $request
     * @return RedirectResponse
     */
    public function clear( Request $request ): RedirectResponse
    {
        try {
            ErrorLog::truncate();
            $request->session()->flash('status', 'All logs has been cleared.');
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Something went wrong, while clearing the logs.');
        }

        return redirect()->back();
    }
}
