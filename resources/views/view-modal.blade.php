<div class="offcanvas-header flex-column">
    <div class="full-log-view">
        <h6>Error Message:</h6>
        <h3 class="m-0" id="offcanvasRightLabel">{{ $errorLog->message }}</h3>
        <small>
            <span class="fw-bold">Request URL:</span>
            <span>{{ $errorLog->url }}</span>
        </small>

        <div class="small">
            <a href="{{ route('error-lens.view', ['id' => $errorLog->id]) }}" target="_blank" title="Full View" class="text-decoration-none">
                <span>View in full screen</span>
            </a>
        </div>
    </div>
</div>
<div class="offcanvas-body">
    <div class="row">
        <div class="col-lg-8">
            @if($errorLog->error)
                <div class="my-4">
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
            
            <div class="my-4">
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

<link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/highlight.min.css') }}" />

<script src="{{ asset('vendor/error-lens/assets/js/highlight.min.js') }}"></script>
<script src="{{ asset('vendor/error-lens/assets/js/languages_json.min.js') }}"></script>
