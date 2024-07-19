<?php

namespace Narolalabs\ErrorLens\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Narolalabs\ErrorLens\Http\Requests\ArchiveErrorLogRequest;
use Narolalabs\ErrorLens\Models\ArchivedErrorLog;
use Narolalabs\ErrorLens\Models\ErrorLog;
use Illuminate\Routing\Controller;
use Narolalabs\ErrorLens\Traits\ErrorLisingConfigTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class ErrorLogController extends Controller
{
    use ErrorLisingConfigTrait;

    public function index(Request $request)
    {   
        $search = addslashes($request->searchErrorInput);
        $relevant = $request->relevant;
        $groupOccurrence = $request->groupOccurrence == null || $request->groupOccurrence == 1 || $request->groupOccurrence == 'on';

        if ($request->view && !in_array($request->view, static::$queryString)) {
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
                'id',
                'method',
                'url',
                'message',
                'repeated',
                'created_at',
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
                ->where('email', '!=', $errorLog->email)
                ->where('created_at', '>=', now()->subDays(config('error-lens.error_preferences.showRelatedErrorsOfDays')));
        }

        if ($groupOccurrence) {
            $query = $query->whereNull('repeated')
                ->withCount('repeatedLogs');
        }
        
        $query = $query->latest()
            ->paginate($this->getPerPageRecordLenght());

        // dd($query);

        $data['errorLogs'] = $query;
        $data['activeError'] = $this->getTitle($request->view ?? $this->getDefaultFilter());
        $data['groupOccurrence'] = $groupOccurrence;

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

    public function view(Request $request, string $id)
    {
        $errorLog = ErrorLog::findOrFail($id);

        $data['errorLog'] = $errorLog;
        $data['relevantErrors'] = 0;
        if (config('error-lens.error_preferences.showRelatedErrors') && config('error-lens.error_preferences.showRelatedErrorsOfDays')) {
            $data['relevantErrors'] = ErrorLog::where('message', $errorLog->message)
                ->where('email', '!=', $errorLog->email)
                ->where('created_at', '>=', now()->subDays(config('error-lens.error_preferences.showRelatedErrorsOfDays')))
                ->count();
        }

        $errorDetail = collect($data['errorLog']->error)->first();
        $data['trace'] = $errorLog->trace;
        $data['stack'] = $data['errorLog']->stack;
        $data['line'] = $errorLog['error_line'] ? $errorLog['error_line'] : ((($errorDetail && isset($errorDetail['line']))) ? $errorDetail['line'] : '');
        $data['stack_start'] = $data['errorLog']->stack_start;
        $data['stack_end'] = $data['errorLog']->stack_end;

        $data['errorFile'] = $errorLog['error_file'] ? $errorLog['error_file'] : ((($errorDetail && isset($errorDetail['file']))) ? $errorDetail['file'] : '');
        $data['errorCode'] = (($errorDetail && isset($errorDetail['code']))) ? $errorDetail['code'] : $errorLog['status'];

        // Pick the view from blade file instead of cache file 
        if (str_contains($errorLog->message, 'resources\views')) {
            $data['errorFile'] = preg_match('/\(View: (.+)\)/', $errorLog->message, $matches) ? trim($matches[1]) : $data['errorFile'];
        }

        if ($request->ajax()) {
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

    public function clear(Request $request): RedirectResponse
    {
        try {
            ErrorLog::truncate();
            ArchivedErrorLog::truncate();
            Cache::flush();
            $request->session()->flash('status', 'All logs has been cleared.');
        } catch (\Exception $e) {
            $request->session()->flash('error', 'Something went wrong, while clearing the logs.');
        }

        return redirect()->back();
    }
    public function archive_selected(ArchiveErrorLogRequest $request)
    {
        $errorLogIds = explode(',', $request->errorId);
        $isGroupedOccurrence = $request->isGroupedOccurrence ? true : false;

        $errorLogs = ErrorLog::whereIn('id', $errorLogIds);
        if ($isGroupedOccurrence) {
            $errorLogs = $errorLogs->orWhereIn('repeated', $errorLogIds);
        }
        
        $errorLogs->each(function ($errorLog) {
            //getting the record one by one that want to be copied
            //copy them using replicate and setting destination table by setTable()
            $newErrorLog = $errorLog->replicate()->setTable('error_logs_archived');
            $newErrorLog->id = $errorLog->id;
            $newErrorLog->created_at = $errorLog->created_at;
            $newErrorLog->updated_at = $errorLog->updated_at;
            $newErrorLog->save();
            
            // Remove from cache
            $existingData = $errorLog->only(['method','url','status','message','error_file','error_line','stack','stack_start','stack_end','email','ip_address','previous_url','browser','guard']);
            $cacheKey = 'error_log_' . md5(json_encode($existingData));
            Cache::forget($cacheKey);
            
            //add following command if you need to remove records from error-log table
            $errorLog->delete();
        });

        if ($errorLogs) {
            $message = (count($errorLogIds) <= 1 ? 'The error log has' : 'Error logs have') . "  been archived successfully.";
            Session::flash('error-lens-success', $message);
            return redirect()->back();
        }
        Session::flash('error-lens-error', 'There seems to be an issue! Please try again later.');
        return redirect()->back();
    }
}
