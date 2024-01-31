@php
    $isArchivedPage = isset($isArchivedPage) ? true : (request()->route()->getName() === 'error-lens.archived.view');
    $fullPageViewRouteName = $isArchivedPage ? 'error-lens.archived.view' : 'error-lens.view';
    $listingPageViewRoute = $isArchivedPage ? 'error-lens.archived.index' : 'error-lens.index';
@endphp
<a class="close_btn" type="button" data-bs-dismiss="offcanvas" aria-label="Close">
    <svg xmlns="http://www.w3.org/2000/svg" height="16" width="16" viewBox="0 0 512 512"><path d="M470.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L402.7 256 265.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160zm-352 160l160-160c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L210.7 256 73.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0z"></path></svg>
</a>
<div class="offcanvas-header flex-column">
    <div class="full-log-view">
        <h6>Error Message:</h6>
        <h3 class="m-0" id="offcanvasRightLabel">{{ $errorLog->message }}</h3>
        <small>
            <span class="fw-bold">Request URL:</span>
            <span>
                {{ $errorLog->url }}
                <a href="{{ route($fullPageViewRouteName, ['id' => $errorLog->id]) }}" target="_blank" title="View in full page" class="ms-1">
                    <svg fill="#15a4b7" xmlns="http://www.w3.org/2000/svg" height="12" width="12" viewBox="0 0 512 512"><path d="M320 0c-17.7 0-32 14.3-32 32s14.3 32 32 32h82.7L201.4 265.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L448 109.3V192c0 17.7 14.3 32 32 32s32-14.3 32-32V32c0-17.7-14.3-32-32-32H320zM80 32C35.8 32 0 67.8 0 112V432c0 44.2 35.8 80 80 80H400c44.2 0 80-35.8 80-80V320c0-17.7-14.3-32-32-32s-32 14.3-32 32V432c0 8.8-7.2 16-16 16H80c-8.8 0-16-7.2-16-16V112c0-8.8 7.2-16 16-16H192c17.7 0 32-14.3 32-32s-14.3-32-32-32H80z"></path></svg>
                </a>
            </span>
        </small>
    </div>
</div>
<div class="offcanvas-body">
    <div class="row">
        <div class="col-lg-8">
            @if($errorLog->error)
                <div class="mb-3">
                    <div class="card-body error-panel">
                        <h3>Error:</h3>
                        @foreach( $errorLog->error as $errors )
                            <ul class="list-group mb-3">
                                @foreach( $errors as $key => $error )
                                    <li class="list-group-item d-flex align-items-start">
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold">{{ ucwords($key) . ':' }}</div>
                                            <span class="">{{ is_array($error) ? json_encode($error) : $error }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <div class="mb-3">
                <div class="card-body error-panel">
                    <h3>Request Data:</h3>
                    @if($errorLog->request_data)
                        <pre><code class="language-json overflow-auto error-wrapper">{{ json_encode($errorLog->request_data, JSON_PRETTY_PRINT) }}</code></pre>
                    @else
                        <h6 class="m-0">N/A</h6>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="accordion" id="rightside-panel">
                <div class="accordion-item custom_accordion-item">
                    <h2 class="accordion-header" id="heading-incident-report">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#incident-report" aria-expanded="true" aria-controls="incident-report">
                            Incident Reported
                        </button>
                    </h2>
                    <div id="incident-report" class="accordion-collapse collapse show" aria-labelledby="heading-incident-report" data-bs-parent="#rightside-panel">
                        <div class="accordion-body p-0">
                            <div class="card-body">
                                <div class="mb-3 input_custom">
                                    <label class="fw-bold">Occured At</label>
                                    <span class="form-control">{{ date('dS F, Y H:i', strtotime($errorLog->created_at)) }}</span>
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

                                @if($relevantErrors)
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
                                        <a href="{{ route($listingPageViewRoute, ['view' => 'current-year', 'relevant' => $errorLog->id]) }}">{{ $relevantErrors }}</a>
                                    </span>
                                </div>
                                @endif

                                <div class="mb-3 input_custom">
                                    <label class="fw-bold">Was user logged-in?</label>
                                    <div class="form-control">
                                        @if($errorLog->email)
                                            <span class="badge bg-success">Yes</span>
                                            <a href="mailto:{{ $errorLog->email }}">{{ $errorLog->email }}</a>
                                        @else
                                            <span class="badge bg-dark">No</span>
                                        @endif
                                    </div>
                                </div>

                                @if($errorLog->email && $errorLog->guard)
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
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#request-headers" aria-expanded="false" aria-controls="request-headers">
                            Request Headers
                        </button>
                    </h2>
                    <div id="request-headers" class="accordion-collapse collapse" aria-labelledby="heading-request-headers" data-bs-parent="#rightside-panel">
                        <div class="accordion-body p-0">
                            <div class="card-body">
                                @if($errorLog->headers)
                                    @foreach( $errorLog->headers as $key => $header )
                                        <div class="mb-3 input_custom">
                                            <label class="fw-bold">{{ $key }}</label>
                                            <div class="form-control">
                                                @foreach( $header as $value )
                                                    <span class="content word-break-all {{ strlen($value) > 130 ? 'ellipsis-content line-clamp-3' : '' }}">{{ $value }}</span>
                                                    @if(strlen($value) > 130)
                                                        <small class="sidebar-view-more text-center text-primary cursor-pointer">View more</small>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
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
            </div>            
        </div>
    </div>
</div>

<script>
    // Apply bootstrap tooltip for the elements
    var toolTipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    if (toolTipElements.length) {
        toolTipElements.forEach(function(item) {
            new bootstrap.Tooltip(item, {
                boundary: document.body
            })
        })
    }
</script>
<link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/highlight.min.css') }}" />

<script src="{{ asset('vendor/error-lens/assets/js/highlight.min.js') }}"></script>
<script src="{{ asset('vendor/error-lens/assets/js/languages_json.min.js') }}"></script>
