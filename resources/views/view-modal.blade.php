<div class="offcanvas-header">
    <div>
        <h6>Error Message:</h6>
        <h3 id="offcanvasRightLabel">{{ $errorLog->message }}</h3>
        <small>
            <span class="fw-bold">Request URL:</span>
            <span>{{ $errorLog->url }}</span>
        </small>
        <small>
            <a href="{{ route('error-lens.view', ['id' => $errorLog->id]) }}" target="_blank" title="Full View">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#0d6efd" x="0px" y="0px" width="20" height="20" viewBox="0 0 32 32">
                    <path d="M 18 5 L 18 7 L 23.5625 7 L 11.28125 19.28125 L 12.71875 20.71875 L 25 8.4375 L 25 14 L 27 14 L 27 5 Z M 5 9 L 5 27 L 23 27 L 23 14 L 21 16 L 21 25 L 7 25 L 7 11 L 16 11 L 18 9 Z"></path>
                </svg>
            </a>
        </small>
    </div>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
</div>
<div class="offcanvas-body">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Error:</h6>
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

        <div class="col-lg-4">
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
    </div>
</div>

<link rel="stylesheet" href="{{ asset('vendor/error-lens/assets/css/highlight.min.css') }}" />

<script src="{{ asset('vendor/error-lens/assets/js/highlight.min.js') }}"></script>
<script src="{{ asset('vendor/error-lens/assets/js/languages_json.min.js') }}"></script>
