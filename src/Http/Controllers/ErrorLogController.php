<?php

namespace Narolalabs\ErrorLens\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Narolalabs\ErrorLens\Http\Requests\ArchiveErrorLogRequest;
use Narolalabs\ErrorLens\Models\ErrorLog;
use Illuminate\Routing\Controller;
use Narolalabs\ErrorLens\Traits\ErrorLisingConfigTrait;

class ErrorLogController extends Controller
{
    use ErrorLisingConfigTrait;

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
            'id', 'method', 'url', 'message', 'created_at',
        ]);

        if ($search) {
            $query = $query->where(function ($subQuery) use ($search) {
                $subQuery->orWhere('url', 'LIKE', "%$search%");
                $subQuery->orWhere('message', 'LIKE', "%$search%");
            });
        }
        if ($relevant && config('error-lens.error_preferences.showRelatedErrors') && config('error-lens.error_preferences.showRelatedErrorsOfDays')) {
            $request->view = 'relevant';
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
