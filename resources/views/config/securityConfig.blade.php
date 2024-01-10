<div class="card card_custom">
    <div class="card-header">
        <h4>Security</h4>
    </div>
    <div class="custom_table p-4">
        <div class="table-responsive">
            <form action="{{ route('error-lens.config.store') }}" method="post">
                @csrf
                <input type="hidden" name="type" value="security">
                <div class="form-group mb-3">
                    <div class="form-check form-switch">
                        <label class="form-check-label" for="storeRequestedData">Store Requested
                            Data</label>
                        <input class="form-check-input" type="checkbox" role="switch"
                            name="storeRequestedData" id="storeRequestedData"
                            {{ @$configurations['security.storeRequestedData'] == 1 ? 'checked' : '' }}
                            onchange="">
                        <div class="invalid-feedback">
                            {{ $errors->first('storeRequestedData') }}
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3 confidentialFieldNames">
                    <label for="confidentialFieldNames">Confidential Field Names:</label>
                    <select class="form-control confidentialFieldNamesInput w-100" multiple="multiple"
                        name="confidentialFieldNames[]">
                        @if (old('confidentialFieldNames', @$configurations['security.confidentialFieldNames']))
                            @foreach (old('confidentialFieldNames', @$configurations['security.confidentialFieldNames']) as $fieldName)
                                <option selected="selected">{{ $fieldName }}</option>
                            @endforeach
                        @endif
                    </select>
                    <small class="text-danger">
                        {{ $errors->first('confidentialFieldNames') }}
                    </small>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>