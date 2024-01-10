<?php

namespace Narolalabs\ErrorLens\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SecurityConfigRequest extends FormRequest
{

    /**
     * Modify requested data before validation
     */
    protected function prepareForValidation()
    {
        if ($this->type == 'error_preferences') {
            $this->merge([
                'autoDeleteLog' => ($this->autoDeleteLog) ? '1' : '0',
                'showRelatedErrors' => ($this->showRelatedErrors) ? '1' : '0',
                'logDeleteAfterDays' => ($this->logDeleteAfterDays) ?? '1',
                'showRelatedErrorsOfDays' => ($this->showRelatedErrorsOfDays) ?? '1',
            ]);
        }
        else if ($this->type == 'security') {
            $this->merge([
                'storeRequestedData' => ($this->storeRequestedData) ? '1' : '0'
            ]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $validationRules = [
            'type' => ['required', Rule::in(['error_preferences','security'])]
        ];

        if ($this->type == 'error_preferences') {
            $validationRules['severityLevel'] = ['nullable', 'array', Rule::in(['1xx','2xx','3xx','4xx','5xx'])];
            $validationRules['autoDeleteLog'] = ['required', 'in:0,1'];
            $validationRules['logDeleteAfterDays'] = ['required_if:autoDeleteLog,1', 'nullable', 'numeric', 'between:1,365'];
            $validationRules['showRelatedErrors'] = ['required', 'in:0,1'];
            $validationRules['showRelatedErrorsOfDays'] = ['required_if:showRelatedErrors,1', 'nullable', 'numeric', 'between:1,60'];
            $validationRules['skipErrorCodes'] = ['nullable', 'array'];
        }
        else if ($this->type == 'security') {
            $validationRules['confidentialFieldNames'] = 'required_if:storeRequestedData,==,1|array';
        }
        return $validationRules;
    }

    public function messages()
    {
        return [
            'confidentialFieldNames.required_if' => 'The confidential field names field is required when :other is checked.',
            'logDeleteAfterDays.required_if' => 'The :attribute field is required when :other is checked.',
            'showRelatedErrorsOfDays.required_if' => 'The :attribute to field is required when :other is checked.',
        ];
    }

    /**
     * Rename the attributes
     *
     * @return void
     */
    public function attributes()
    {
        return [
            'storeRequestedData' => 'store requested data',
            'confidentialFieldNames' => 'confidential field names',
            'severityLevel' => 'severity level',
            'severityLevel' => 'severity level',
            'autoDeleteLog' => 'auto delete log',
            'logDeleteAfterDays' => 'log delete after',
            'showRelatedErrors' => 'show related error logs',
            'showRelatedErrorsOfDays' => 'related logs up to',
            'skipErrorCodes' => 'skip error codes'
        ];
    }
}
