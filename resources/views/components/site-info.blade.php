<div class="card my-4">
    <h5 class="card-header bg-dark text-white">Website Information</h5>
    <div class="card-body">
        <div class="mb-3">
            <label class="fw-bold">Base URL</label>
            <span class="form-control">{{ config('app.url') }}</span>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Environment</label>
            <span class="form-control">{{ config('app.env') }}</span>
        </div>

        <div class="mb-3">
            <label class="fw-bold">PHP Version</label>
            <span class="form-control">{{ PHP_VERSION }}</span>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Laravel Version</label>
            <span class="form-control">{{ Illuminate\Foundation\Application::VERSION }}</span>
        </div>

        <div class="mb-3">
            <label class="fw-bold">Timezone</label>
            <span class="form-control">{{ date_default_timezone_get() }}</span>
        </div>
    </div>
</div>
