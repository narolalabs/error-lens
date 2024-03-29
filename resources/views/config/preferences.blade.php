<div class="card card_custom">
    <div class="card-header">
        <h4>Error Preferences</h4>
    </div>
    <div class="custom_table p-4">
        <div class="table-responsive">
            <form action="{{ route('error-lens.config.store') }}" method="post">
                @csrf
                <input type="hidden" name="type" value="error_preferences">
                <div class="mb-2">
                    <h5 class="fw-bold text-secondary d-inline">
                        Severity Level
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-exclamation-circle-fill cursor-pointer"
                            viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="System will log only errors of the chosen severity level">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                        </svg>
                    </h5>
                </div>

                <div class="form-group mb-3">
                    <label for="severityLevel">Severity Level:</label>
                    <select class="form-control severityLevel w-100" multiple="multiple"
                        name="severityLevel[]">
                        <option value="4xx" {{ @$configurations['error_preferences.severityLevel'] && in_array('4xx', old('severityLevel', @$configurations['error_preferences.severityLevel'] ?? [])) ? 'selected' : '' }}>
                            4xx Client Error
                        </option>
                        <option value="5xx" {{ @$configurations['error_preferences.severityLevel'] && in_array('5xx', old('severityLevel', @$configurations['error_preferences.severityLevel'] ?? [])) ? 'selected' : '' }}>
                            5xx Server Error
                        </option>
                    </select>
                    <small class="text-danger">
                        {{ $errors->first('severityLevel') }}
                    </small>
                </div>

                <div class="mt-5 mb-2">
                    <h5 class="fw-bold text-secondary d-inline">
                        Customize Environment
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-exclamation-circle-fill cursor-pointer"
                            viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="In case you don't wish to track errors in production mode and want to set another custom environment, you can do so.">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                        </svg>
                    </h5>
                </div>
                <div class="d-flex  justify-content-between  align-items-top">
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="haventProductionEnv">Have't Production Environment?</label>
                            <input class="form-check-input" type="checkbox" role="switch"
                                name="haventProductionEnv" id="haventProductionEnv"
                                {{ old('haventProductionEnv', @$configurations['error_preferences.haventProductionEnv']) ? 'checked' : '' }}
                                onchange="">
                            <div class="invalid-feedback">
                                {{ $errors->first('haventProductionEnv') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3 customEnvName">
                        <label for="customEnvName">Your Environment Name:</label>
                        <input type="text" class="form-control" placeholder="staging" {{ old('customEnvName', @$configurations['error_preferences.customEnvName']) }} name="customEnvName" value="{{ old('customEnvName', @$configurations['error_preferences.customEnvName'] ?? config('app.env')) }}">
                        <small class="text-danger">
                            {{ $errors->first('customEnvName') }}
                        </small>
                    </div>
                </div>

                <div class="mt-5 mb-2">
                    <h5 class="fw-bold text-secondary d-inline">
                        Auto Delete Logs
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-exclamation-circle-fill cursor-pointer"
                            viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Errors are automatically deleted after the selected number of days.">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                        </svg>
                    </h5>
                </div>
                <div class="d-flex  justify-content-between  align-items-top">
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="autoDeleteLog">Auto Delete Logs?</label>
                            <input class="form-check-input" type="checkbox" role="switch"
                                name="autoDeleteLog" id="autoDeleteLog"
                                {{ old('autoDeleteLog', @$configurations['error_preferences.autoDeleteLog']) ? 'checked' : '' }}
                                onchange="">
                            <div class="invalid-feedback">
                                {{ $errors->first('autoDeleteLog') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3 logDeleteAfterDays">
                        <label for="logDeleteAfterDays">Log Delete After:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="30" {{ old('logDeleteAfterDays', @$configurations['error_preferences.logDeleteAfterDays']) }} name="logDeleteAfterDays" value="{{ old('logDeleteAfterDays', @$configurations['error_preferences.logDeleteAfterDays']) }}">
                            <span class="input-group-text bg-primary text-white" id="logDeleteAfterDays">Days</span>
                        </div>
                        <small class="text-danger">
                            {{ $errors->first('logDeleteAfterDays') }}
                        </small>
                    </div>
                </div>

                <div class="mt-5 mb-2">
                    <h5 class="fw-bold text-secondary d-inline">
                        Related Errors
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-exclamation-circle-fill cursor-pointer"
                            viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="If you want to verify whether the same error occurs within the specified timeframe, you can enable it and select the days to display the related errors generated during that period.">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                        </svg>
                    </h5>
                </div>
                <div class="d-flex  justify-content-between  align-items-top">
                    <div class="form-group mb-3">
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="showRelatedErrors">Show Related
                                Error Logs</label>
                            <input class="form-check-input" type="checkbox" role="switch"
                                name="showRelatedErrors" id="showRelatedErrors"
                                {{ old('showRelatedErrors', @$configurations['error_preferences.showRelatedErrors']) ? 'checked' : '' }}
                                onchange="">
                            <div class="invalid-feedback">
                                {{ $errors->first('showRelatedErrors') }}
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3 showRelatedErrorsOfDays">
                        <label for="showRelatedErrorsOfDays">Related Logs up to:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="30"
                                aria-label="30" aria-describedby="showRelatedErrorsOfDays" name="showRelatedErrorsOfDays" value="{{ old('showRelatedErrorsOfDays', @$configurations['error_preferences.showRelatedErrorsOfDays']) }}">
                            <span class="input-group-text bg-primary text-white" id="showRelatedErrorsOfDays">Days</span>
                        </div>
                        <small class="text-danger">
                            {{ $errors->first('showRelatedErrorsOfDays') }}
                        </small>
                    </div>
                </div>

                <div class="mt-5 mb-2">
                    <h5 class="fw-bold text-secondary d-inline">
                        Skip Errors
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" class="bi bi-exclamation-circle-fill cursor-pointer"
                            viewBox="0 0 16 16" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="If you have chosen a specific severity level and you wish to exclude certain
                            errors based on their error codes within that severity level, you can achieve
                            this by adding specific error codes.">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4m.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2" />
                        </svg>
                    </h5>
                </div>
                <div class="form-group mb-3">
                    <label for="skipErrorCodes">Skip error codes:</label>
                    <select class="form-control skipErrorCodesInput w-100"
                        multiple="multiple" name="skipErrorCodes[]">
                        @if (old('skipErrorCodes', @$configurations['error_preferences.skipErrorCodes']))
                            @foreach (old('skipErrorCodes', @$configurations['error_preferences.skipErrorCodes']) as $fieldName)
                                <option selected="selected">{{ $fieldName }}</option>
                            @endforeach
                        @endif
                    </select>
                    <small class="text-danger">
                        {{ $errors->first('skipErrorCodes') }}
                    </small>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>