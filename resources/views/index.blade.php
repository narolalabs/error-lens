@extends('error-lens::layouts.app')

@section('content')
<div class="row">
    <div class="col-md-9">
        <h1 class="my-4">Dashboard</h1>

        <div class="row mb-3">
            <div class="col-lg-3">
                <div class="card bg-dark text-white">
                    <div class="card-header text-uppercase">
                        <h6>Today's ERRORS</h6>
                        <small>{{ date('d M, Y') }}</small>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <h1 class="mb-0">{{ $today_errors_count }}</h1>
                        <a href="{{ route('error-lens.index', ['view' => 'today']) }}" class="text-primary text-decoration-none">View</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card bg-dark text-white">
                    <div class="card-header text-uppercase">
                        <h6>Yesterday's ERRORS</h6>
                        <small>{{ date('d M, Y', strtotime('yesterday')) }}</small>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <h1 class="mb-0">{{ $yesterday_errors_count }}</h1>
                        <a href="{{ route('error-lens.index', ['view' => 'yesterday']) }}" class="text-primary text-decoration-none">View</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="card bg-dark text-white">
                    <div class="card-header text-uppercase">
                        <h6>Last Month Errors</h6>
                        <small>{{ date('F-Y', strtotime('last month')) }}</small>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <h1 class="mb-0">{{ $last_month_errors_count }}</h1>
                        <a href="{{ route('error-lens.index', ['view' => 'last-month']) }}" class="text-primary text-decoration-none">View</a>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="card bg-dark text-white">
                    <div class="card-header text-uppercase">
                        <h6>All Errors</h6>
                        <small>Year - {{ date('Y') }}</small>
                    </div>
                    <div class="card-body d-flex justify-content-between align-items-end">
                        <h1 class="mb-0">{{ $current_year_errors_count }}</h1>
                        <a href="{{ route('error-lens.index', ['view' => 'current-year']) }}" class="text-primary text-decoration-none">View</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <h2>{{ $activeError }}</h2>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-inverse">
                <thead>
                    <tr>
                        <th>URL</th>
                        <th>Message</th>
                        <th>Occured At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse( $errorLogs as $errorLog )
                        <tr>
                            <td width="30%">{{ $errorLog->url }}</td>
                            <td>{{ $errorLog->message }}</td>
                            <td width="20%">{{ $errorLog->created_at->format('dS F, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('error-lens.view', [ 'id' => $errorLog->id ]) }}" class="btn btn-link text-decoration-none view-error">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <h6>No errors reported.</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            {{ $errorLogs->links() }}
        </div>
    </div>

    <div class="col-md-3">
        <x-error-lens::site-info />
    </div>
</div>

@endsection
