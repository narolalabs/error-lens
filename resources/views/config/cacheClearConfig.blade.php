<div class="card card_custom mt-4">
    <div class="card-header">
        <h4>Cache</h4>
    </div>
    <div class="custom_table p-4">
        <div class="table-responsive">
            <form action="{{ route('error-lens.config.cache-clear') }}" method="post">
                @csrf
                <input type="hidden" name="type" value="clearCache">
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Cache Clear</button>
                </div>
                <div class="mt-3">
                    <b class="text-danger">[Note: After making any configuration changes, please clear the cache to ensure that the changes take effect.]</b>
                </div>
            </form>
        </div>
    </div>
</div>