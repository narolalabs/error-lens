@props(['showHeader' => true])
<div {{ $attributes->merge(['class' => 'card card_custom']) }}>
    @if ($showHeader)
        <div class="card-header">
            <h4>Website Information</h4>
        </div>
    @endif
    <div class="card-body">
        <div class="mb-3 input_custom">
            <label class="fw-bold">Site Name</label>
            <span class="form-control">{{ config('app.name') }}</span>
        </div>

        <div class="mb-3 input_custom">
            <label class="fw-bold">Base URL</label>
            <span class="form-control">{{ config('app.url') }}</span>
        </div>

        <div class="mb-3 input_custom">
            <label class="fw-bold">Environment</label>
            <span class="form-control">{{ config('app.env') }}</span>
        </div>

        <div class="mb-3 input_custom">
            <label class="fw-bold">PHP Version</label>
            <span class="form-control">{{ PHP_VERSION }}</span>
        </div>

        <div class="mb-3 input_custom">
            <label class="fw-bold">Laravel Version</label>
            <span class="form-control">{{ Illuminate\Foundation\Application::VERSION }}</span>
        </div>

        <div class="mb-3 input_custom">
            <label class="fw-bold">Timezone</label>
            <span class="form-control">{{ date_default_timezone_get() }}</span>
        </div>
    </div>
</div>
