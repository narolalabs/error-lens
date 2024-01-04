@extends('error-lens::layouts.app')

@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    <h2 class="my-4">Configurations</h2>
    <x-error-lens::alert-message />
    <div class="row">
        <div class="col-md-6">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card_custom">
                        <h4 class="card-header">Security</h4>
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
                                        <select class="form-control js-example-tokenizer w-100" multiple="multiple" name="confidentialFieldNames[]">
                                            @foreach (old('confidentialFieldNames', @$configurations['security.confidentialFieldNames']) as $fieldName)
                                                <option selected="selected">{{ $fieldName }}</option>    
                                            @endforeach
                                        </select>
                                        {{-- <option selected="selected">orange</option>
                                        <option>white</option>
                                        <option selected="selected">purple</option> --}}
                                        {{-- <textarea class="form-control" id="confidentialFieldNames" rows="3" name="confidentialFieldNames"
                                            placeholder="Comma separated field names">{{ old('confidentialFieldNames', @$configurations['security.confidentialFieldNames']) }}</textarea> --}}
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var storeRequestedData = document.getElementById('storeRequestedData');
            var confidentialFieldNamesWrapper = document.getElementsByClassName('confidentialFieldNames');
            var confidentialFieldNames = document.getElementById('confidentialFieldNames');

            storeRequestedData.addEventListener('change', function(event) {
                if (storeRequestedData.checked) {
                    confidentialFieldNamesWrapper[0].classList.remove('d-none');
                } else {
                    confidentialFieldNamesWrapper[0].classList.add('d-none');
                    confidentialFieldNames.value = '';
                }
            });

            storeRequestedData.dispatchEvent(new Event("change"));

            
            $(".js-example-tokenizer").select2({
                tags: true,
                tokenSeparators: [',', ' ']
            })
        });
    </script>
@endsection
