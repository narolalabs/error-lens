<?php

namespace Narolalabs\ErrorLens\Http\Controllers;

use Illuminate\Http\Request;
use Narolalabs\ErrorLens\Models\ArchivedErrorLog;
use Illuminate\Routing\Controller;

class ArchivedErrorLogController extends Controller
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

    /**
     * List out all the archived error and their relevant counts
     *
     * @param Request $request
     * @return void
     */
    public function index( Request $request )
    {
        $activeError = current(static::$queryString);
        $search = addslashes($request->searchErrorInput);
        $relevant = $request->relevant;

        if ( $request->view && !in_array($request->view, static::$queryString) ) {
            return redirect()->route('error-lens.index');
        }

        $data = [
            'today_errors_count' => ArchivedErrorLog::select('id')->getFilters(date('Y-m-d'))->count(),
            'yesterday_errors_count' => ArchivedErrorLog::select('id')->getFilters(date('Y-m-d', strtotime('yesterday')))->count(),
            'last_month_errors_count' => ArchivedErrorLog::select('id')->getFilters(date('Y-m', strtotime('last month')))->count(),
            'current_year_errors_count' => ArchivedErrorLog::select('id')->getFilters(date('Y'))->count(),
        ];
        $query = ArchivedErrorLog::getFilters(
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
            $errorLog = ArchivedErrorLog::findOrFail($relevant);
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

    /**
     * Show the detail of the archived error detail
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function view( Request $request, string $id )
    {
        $errorLog = ArchivedErrorLog::findOrFail($id);

        $data['errorLog'] = $errorLog;
        $data['relevantErrors'] = 0;
        if (config('error-lens.error_preferences.showRelatedErrors') && config('error-lens.error_preferences.showRelatedErrorsOfDays')) {
            $data['relevantErrors'] = ArchivedErrorLog::where('message', $errorLog->message)
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

    /**
     * Delete selected archived error logs
     *
     * @param Request $request
     * @return void
     */
    public function destroy(Request $request)
    {
        $archivedErrorLogIds = explode(',', $request->archiveErrorId);
        $deleteArchivedErrorLogs = ArchivedErrorLog::whereIn('id', $archivedErrorLogIds)->delete();

        if ($deleteArchivedErrorLogs) {
            $message = (count($archivedErrorLogIds) <= 1 ? 'The archived error log has' : 'Archived error logs have')."  been deleted successfully.";
            return redirect()->back()->withSuccess($message);
        }
        return redirect()->back()->withError('There seems to be an issue! Please try again later.');
    }
}
