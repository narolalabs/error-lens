<div class="card card_custom" data-index="securityConfig">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Security</h4>
            <div class="cursor-pointer cursor-grab">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-arrows-move" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M7.646.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 1.707V5.5a.5.5 0 0 1-1 0V1.707L6.354 2.854a.5.5 0 1 1-.708-.708zM8 10a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 14.293V10.5A.5.5 0 0 1 8 10M.146 8.354a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L1.707 7.5H5.5a.5.5 0 0 1 0 1H1.707l1.147 1.146a.5.5 0 0 1-.708.708zM10 8a.5.5 0 0 1 .5-.5h3.793l-1.147-1.146a.5.5 0 0 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L14.293 8.5H10.5A.5.5 0 0 1 10 8" />
                </svg>
            </div>
        </div>
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