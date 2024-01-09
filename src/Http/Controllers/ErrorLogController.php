<?php

namespace Narolalabs\ErrorLens\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Narolalabs\ErrorLens\Http\Requests\ArchiveErrorLogRequest;
use Narolalabs\ErrorLens\Models\ErrorLog;
use Illuminate\Routing\Controller;
use Narolalabs\ErrorLens\Http\Requests\SecurityConfigRequest;
use Narolalabs\ErrorLens\Models\ErrorLogConfig;

class ErrorLogController extends Controller
{
    protected static array $queryString = [
        'today', 'yesterday', 'last-month', 'current-year',
    ];

    protected static string $defaultFilter = 'today';

    protected static int $perPageRecordLenght = 20;

    protected function getDefaultFilter(): string
    {
        return static::$defaultFilter;
    }

    protected function getPerPageRecordLenght(): int
    {
        return static::$perPageRecordLenght;
    }

    protected function getTitle($queryString)
    {
        $title = '';
        switch ($queryString) {
            case 'today': 
                $title = "Today's errors";
                break;
            case 'yesterday':
                $title = "Yesterday's errors";
                break;
            case 'last-month':
                $title = 'Last month errors';
                break;
            case 'current-year':
                $title = 'Current year errors';
                break;
        }
        return $title;
    }

    protected function getFilterValue($view): string
    {
        $date = date('Y-m-d');
        switch ($view) {
            case 'today': 
                $date = date('Y-m-d');
                break;
            case 'yesterday':
                $date = date('Y-m-d', strtotime('yesterday'));
                break;
            case 'last-month':
                $date = date('Y-m', strtotime('last month'));
                break;
            case 'current-year':
                $date = date('Y');
                break;
        }
        return $date;
    }

    public function index( Request $request )
    {
        $activeError = current(static::$queryString);
        $search = addslashes($request->searchErrorInput);
        $relevant = $request->relevant;

        if ( $request->view && !in_array($request->view, static::$queryString) ) {
            return redirect()->route('error-lens.index');
        }

        $data = [
            'today_errors_count' => ErrorLog::select('id')->getFilters(date('Y-m-d'))->count(),
            'yesterday_errors_count' => ErrorLog::select('id')->getFilters(date('Y-m-d', strtotime('yesterday')))->count(),
            'last_month_errors_count' => ErrorLog::select('id')->getFilters(date('Y-m', strtotime('last month')))->count(),
            'current_year_errors_count' => ErrorLog::select('id')->getFilters(date('Y'))->count(),
        ];
        $query = ErrorLog::getFilters(
            $this->getFilterValue($request->view ?? $this->getDefaultFilter())
        )
        ->select([
            'id', 'url', 'message', 'created_at',
        ]);

        if ($search) {
            $query = $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('url', 'LIKE', "%$search%");
                $subQuery->orWhere('message', 'LIKE', "%$search%");
            });
        }
        if ($relevant && config('error-lens.error_preferences.showRelatedErrors') && config('error-lens.error_preferences.showRelatedErrorsOfDays')) {
            $errorLog = ErrorLog::findOrFail($relevant);
            $query = $query->where('message', $errorLog->message)
            ->where('email', '!=',  $errorLog->email)
            ->where('created_at', '>=',  now()->subDays(config('error-lens.error_preferences.showRelatedErrorsOfDays')));
        }
        
        $query = $query->latest()
        ->paginate($this->getPerPageRecordLenght());

        $data['errorLogs'] = $query;
        $data['activeError'] = $this->getTitle($request->view ?? $this->getDefaultFilter());

        if ($request->ajax()) {
            $data['viewRouteName'] = (request()->route()->getName() === 'error-lens.index.search') ? 'error-lens.view' : 'error-lens.archived.view';
            return response()->json([
                'flag' => true,
                'message' => 'Error log listing fetch successfully.',
                'data' => [
                    'view' => view('error-lens::error-list', $data)->render()
                ]
            ]);    
        }
        return view('error-lens::index', $data);
    }

    public function view( Request $request, string $id )
    {
        $errorLog = ErrorLog::findOrFail($id);

        $data['errorLog'] = $errorLog;
        $data['relevantErrors'] = 0;
        if (config('error-lens.error_preferences.showRelatedErrors') && config('error-lens.error_preferences.showRelatedErrorsOfDays')) {
            $data['relevantErrors'] = ErrorLog::where('message', $errorLog->message)
                    ->where('email', '!=',  $errorLog->email)
                    ->where('created_at', '>=',  now()->subDays(config('error-lens.error_preferences.showRelatedErrorsOfDays')))
                    ->count();
        }
        
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

    public function config(Request $request)
    {
        $configurations = ErrorLogConfig::where('key', 'NOT LIKE', 'authenticate.%')->pluck('value', 'key');

        $multiSelectedValues = ['security.confidentialFieldNames', 'error_preferences.severityLevel', 'error_preferences.skipErrorCodes'];
        foreach ($multiSelectedValues as $multiSelectedValue) {
            if (isset($configurations[$multiSelectedValue]) && !empty($configurations[$multiSelectedValue])) {
                // Convert comma separated string to array 
                $configurations[$multiSelectedValue] = explode(',', @$configurations[$multiSelectedValue]);
            }
        }

        return view('error-lens::config.config', compact('configurations'));
    }

    public function config_store(SecurityConfigRequest $request)
    {
        if ($request->type == 'error_preferences') {
            $data = collect($request->all())->only(['autoDeleteLog', 'logDeleteAfterDays', 'showRelatedErrors', 'showRelatedErrorsOfDays', 'severityLevel', 'skipErrorCodes']);

            $data = $data->map(function ($value, $key) use ($request) {
                return [
                    'key' => $request->type . '.' . $key,
                    'value' => in_array($key, ['severityLevel', 'skipErrorCodes']) ? implode(',', array_filter(array_map('trim', $value))) : $value,
                ];
            })->toArray();

            $update = ErrorLogConfig::upsert($data, ['key']);
            if ($update) {
                // clear cache
                // $this->call('cache:clear');
                // $this->call('config:cache');
                return redirect()->back()->withSuccess('Preferences have been updated successfully.');
            }

        } else if ($request->type == 'security') {
            $data = collect($request->all())->only(['storeRequestedData', 'confidentialFieldNames']);

            $data = $data->map(function ($value, $key) use ($request) {
                return [
                    'key' => $request->type . '.' . $key,
                    'value' => ($key == 'confidentialFieldNames') ? implode(',', array_filter(array_map('trim', $value))) : $value,
                ];
            })->toArray();

            $update = ErrorLogConfig::upsert($data, ['key']);
            if ($update) {
                // clear cache
                // $this->call('cache:clear');
                // $this->call('config:cache');
                return redirect()->back()->withSuccess('Security configurations have been updated successfully.');
            }
        }

        return redirect()->back()->withError('There seems to be an issue! Please try again later.');
    }

    public function archive_selected(ArchiveErrorLogRequest $request)
    {
        $errorLogIds = explode(',', $request->errorId);
        $errorLogs = ErrorLog::whereIn('id', $errorLogIds)->each(function ($errorLog) {
            //getting the record one by one that want to be copied
            //copy them using replicate and setting destination table by setTable()
            $newErrorLog = $errorLog->replicate()->setTable('error_logs_archived');
            $newErrorLog->id = $errorLog->id;
            $newErrorLog->created_at = $errorLog->created_at;
            $newErrorLog->updated_at = $errorLog->updated_at;
            $newErrorLog->save();

            //add following command if you need to remove records from error-log table
            $errorLog->delete();
        });

        if ($errorLogs) {
            $message = (count($errorLogIds) <= 1 ? 'The error log has' : 'Error logs have')."  been archived successfully.";
            return redirect()->back()->withSuccess($message);
        }
        return redirect()->back()->withError('There seems to be an issue! Please try again later.');
    }
}
