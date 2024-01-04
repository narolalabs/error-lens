@if(session('success') || session('error'))
<div class="row">
    <div class="col-md-12">
        <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }}  alert-dismissible fade show" role="alert">
            {{ session('success') ?? session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>
@endif