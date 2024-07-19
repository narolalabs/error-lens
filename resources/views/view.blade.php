@extends('error-lens::layouts.app')

@section('style')
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/themes/prism.min.css" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism-themes/1.5.0/prism-material-light.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-numbers/prism-line-numbers.min.css" />
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-highlight/prism-line-highlight.min.css" />
@endsection

@section('content')
    @php
        $isArchivedPage = request()->route()->getName() === 'error-lens.archived.view';
        $listingPageViewRoute = $isArchivedPage ? 'error-lens.archived.index' : 'error-lens.index';
    @endphp
    <div class="row" id="full-view">
        <div class="col-sm-12">
            <div id="sticky-wrap" class="my-4 d-flex flex-column full-log-view">
                <h2>
                    @if ($errorCode)
                        <span class="badge rounded-pill bg-danger p-2">{{ $errorCode }}</span>
                    @endif <span class="add-read-more show-less-content">{{ $errorLog->message }}</span>
                </h2>
                <small>
                    <span class="fw-bold">Request URL:</span>
                    <span>({{ $errorLog->method }}) {{ $errorLog->url }}</span>
                </small>
            </div>
        </div>
        <div class="col-md-9">
            @if ($errorLog->error)
                @if ($stack && $line)
                    <p class="fw-bolder">
                        {{ $errorFile }} :{{ $line }}
                    </p>
                    <div class="error-page mb-5">
                        <pre class="line-numbers" data-start="{{ $stack_start }}" data-end="{{ $stack_end }}"
                            data-line="{{ $line - $stack_start + 1 }}"><code class="language-php">{{ $stack }}</code></pre>
                    </div>
                @else
                    <div class="card-body error-panel">
                        <h3>Error:</h3>
                        @foreach ($errorLog->error as $errors)
                            <ul class="list-group mb-3">
                                @foreach ($errors as $key => $error)
                                    <li class="list-group-item d-flex align-items-start">
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold">{{ ucwords($key) . ':' }}</div>
                                            <span>{{ is_array($error) ? json_encode($error) : $error }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="mb-3">
                    <p class="fw-bolder">
                        Error Tracing:
                    </p>
                    <div class="error-page mb-5">
                        <pre class="line-numbers"><code class="language-php">{{ print_r($trace) }}</code></pre>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-3">
            <div class="mb-4 accordion" id="rightside-panel">
                @if ($errorLog->request_data)
                    <div class="accordion-item custom_accordion-item">
                        <h2 class="accordion-header" id="requestedData">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#requested-data" aria-expanded="true" aria-controls="requested-data">
                                Requested Data
                            </button>
                        </h2>
                        <div id="requested-data" class="accordion-collapse collapse" aria-labelledby="requestedData"
                            data-bs-parent="#rightside-panel">
                            <div class="accordion-body p-0">
                                <div class="card-body">
                                    @foreach ($errorLog->request_data as $key => $value)
                                        <div class="mb-3 input_custom">
                                            <label class="fw-bold">{{ $key }}</label>
                                            <span class="form-control">{{ $value }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="accordion-item custom_accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#incident-report" aria-expanded="true" aria-controls="incident-report">
                            Incident Reported
                        </button>
                    </h2>
                    <div id="incident-report" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#rightside-panel">
                        <div class="accordion-body p-0">
                            <div class="card-body">
                                <div class="mb-3 input_custom">
                                    <label class="fw-bold">Occured At</label>
                                    <span
                                        class="form-control">{{ date('dS F, Y H:i', strtotime($errorLog->created_at)) }}</span>
                                </div>

                                <div class="mb-3 input_custom">
                                    <label class="fw-bold">User IP Address</label>
                                    <span class="form-control">{{ $errorLog->ip_address }}</span>
                                </div>

                                <div class="mb-3 input_custom">
                                    <label class="fw-bold">User Browser</label>
                                    <span class="form-control">{{ $errorLog->browser }}</span>
                                </div>

                                <div class="mb-3 input_custom">
                                    <label class="fw-bold">Previous URL</label>
                                    <span class="form-control">{{ $errorLog->previous_url }}</span>
                                </div>

                                @if ($relevantErrors)
                                    <div class="mb-3 input_custom">
                                        <label class="fw-bold">
                                            Relevant Errors
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-exclamation-circle-fill cursor-pointer"
                                                viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Same error for different users.">
                                                <path
                                                    d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                                            </svg>
                                        </label>
                                        <span class="form-control">
                                            <a
                                                href="{{ route($listingPageViewRoute, ['view' => 'current-year', 'relevant' => $errorLog->id]) }}">{{ $relevantErrors }}</a>
                                        </span>
                                    </div>
                                @endif

                                <div class="mb-3 input_custom">
                                    <label class="fw-bold">Was user logged-in?</label>
                                    <div class="form-control">
                                        @if ($errorLog->email)
                                            <span class="badge bg-success">Yes</span>
                                            <a href="mailto:{{ $errorLog->email }}">{{ $errorLog->email }}</a>
                                        @else
                                            <span class="badge bg-dark">No</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($errorLog->email && $errorLog->guard)
                                    <div class="mb-3 input_custom">
                                        <label class="fw-bold">Logged-in guard?</label>
                                        <span class="form-control">{{ $errorLog->guard }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item custom_accordion-item">
                    <h2 class="accordion-header" id="heading-request-headers">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#request-headers" aria-expanded="false" aria-controls="request-headers">
                            Request Headers
                        </button>
                    </h2>
                    <div id="request-headers" class="accordion-collapse collapse"
                        aria-labelledby="heading-request-headers" data-bs-parent="#rightside-panel">
                        <div class="accordion-body p-0">
                            <div class="card-body">
                                @if ($errorLog->headers)
                                    @foreach ($errorLog->headers as $key => $header)
                                        @if (!in_array($key, ['php-auth-pw', 'php-auth-user']))
                                            <div class="mb-3 input_custom">
                                                <label class="fw-bold">{{ $key }}</label>
                                                <div class="form-control">
                                                    @foreach ($header as $value)
                                                        <span
                                                            class="content word-break-all {{ strlen($value) > 130 ? 'ellipsis-content line-clamp-3' : '' }}">{{ $value }}</span>
                                                        @if (strlen($value) > 130)
                                                            <small
                                                                class="sidebar-view-more text-center text-primary cursor-pointer">View
                                                                more</small>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="input_custom">
                                        <div class="fw-bold">N/A</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item custom_accordion-item">
                    <h2 class="accordion-header" id="heading-website-info">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#website-info" aria-expanded="false" aria-controls="website-info">
                            Website Information
                        </button>
                    </h2>
                    <div id="website-info" class="accordion-collapse collapse" aria-labelledby="heading-website-info"
                        data-bs-parent="#rightside-panel">
                        <div class="accordion-body p-0">
                            <x-error-lens::site-info :showHeader="false" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Apply bootstrap tooltip for the elements
            var toolTipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            if (toolTipElements.length) {
                toolTipElements.forEach(function(item) {
                    new bootstrap.Tooltip(item, {
                        boundary: document.body
                    })
                })
            }
        });
    </script>

    {{-- <link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/highlight.min.css') }}" /> --}}

    {{-- <script src="{{ asset('vendor/error-lens/assets/js/highlight.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('vendor/error-lens/assets/js/languages_json.min.js') }}"></script> --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-numbers/prism-line-numbers.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/9000.0.1/plugins/line-highlight/prism-line-highlight.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.22.0/components/prism-json.min.js"></script>
@endsection
