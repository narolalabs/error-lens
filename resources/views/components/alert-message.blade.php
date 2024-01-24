@php
    $customMessage = isset($customMessage) ? $customMessage : null;
@endphp
@if(session('success') || session('error') || (isset($customMessage) && collect($customMessage)->count()))
<div class="row">
    <div class="col-md-12">
        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}  alert-dismissible fade show" role="alert">
            {{ session('success') ?? (collect($customMessage)->first() ?? session('error')) }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif