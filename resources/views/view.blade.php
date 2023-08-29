@extends('error-lens::layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-9">
            <div class="my-4 d-flex flex-column full-log-view">
                <h6>Error Message:</h6>
                <h1>{{ $errorLog->message }}</h1>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h6>Errors</h6>
                    <pre><code class="language-json overflow-auto error-wrapper" style="min-height: 200px">{{ json_encode($errorLog->error, JSON_PRETTY_PRINT) }}</code></pre>
                </div>
            </div>

            <div class="card mb-5">
                <div class="card-body exception-trace-wrapper">
                    <h6>Exception Trace</h6>
                    <pre><code class="language-json overflow-auto error-wrapper">{{ json_encode($errorLog->trace, JSON_PRETTY_PRINT) }}</code></pre>
                </div>

                <a href="javascript: void(0);" class="text-primary text-center text-decoration-none mb-3 view-more">View more</a>
            </div>
        </div>

        <div class="col-md-3">
            <div class="my-4">
                <ul class="list-group">
                    <li class="list-group-item d-flex align-items-start">
                        <div class="d-flex flex-column">
                            <div class="fw-bold">Occure At</div>
                            <span>{{ $errorLog->created_at->format('dS F, Y H:s') }}</span>
                        </div>
                    </li>

                    <li class="list-group-item d-flex align-items-start">
                        <div class="d-flex flex-column">
                            <div class="fw-bold">User IP Address</div>
                            <span>{{ $errorLog->ip_address }}</span>
                        </div>
                    </li>

                    <li class="list-group-item d-flex align-items-start">
                        <div class="d-flex flex-column">
                            <div class="fw-bold">User Browser</div>
                            <span>{{ $errorLog->browser }}</span>
                        </div>
                    </li>

                    <li class="list-group-item d-flex align-items-start">
                        <div class="d-flex flex-column">
                            <div class="fw-bold">Previous URL</div>
                            <span>{{ $errorLog->previous_url }}</span>
                        </div>
                    </li>

                    <li class="list-group-item d-flex align-items-start">
                        <div class="d-flex flex-column">
                            <div class="fw-bold">Was user logged-in?</div>
                            <div>
                                @if($errorLog->email)
                                    <span class="badge bg-success">Yes</span>
                                    <a href="mailto:{{ $errorLog->email }}">{{ $errorLog->email }}</a>
                                @else
                                    <span class="badge bg-dark">No</span>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <x-error-lens::site-info />
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/highlight.min.css') }}" />

    <script src="{{ asset('vendor/error-lens/assets/js/highlight.min.js') }}"></script>
    <script src="{{ asset('vendor/error-lens/assets/js/languages_json.min.js') }}"></script>
@endsection
