@php
    $customMessage = isset($customMessage) ? $customMessage : null;
@endphp
@if(session('error-lens-success') || session('error-lens-error') || (isset($customMessage) && collect($customMessage)->count()))
<div class="row">
    <div class="col-md-12">
        <div class="alert {{ session('error-lens-success') ? 'alert-success' : 'alert-danger' }}  alert-dismissible fade show" role="alert">
            {{ session('error-lens-success') ?? (collect($customMessage)->first() ?? session('error-lens-error')) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif