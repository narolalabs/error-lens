<?php

namespace Narolalabs\ErrorLens\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArchiveErrorLogRequest extends FormRequest
{

    /**
     * Modify requested data before validation
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'archiveErrorId' => explode(',', $this->archiveErrorId)
        ]);
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
        return [
            'archiveErrorId' => 'required|array|exists:error_logs,id'
        ];
    }

    public function messages()
    {
        return [
            'archiveErrorId.required' => 'Please select at least one error log to move archive list.',
            'archiveErrorId.array' => 'Please select at least one error log to move archive list.',
            'archiveErrorId.exists' => 'The selected error log/logs do not exist in the database. Please reload the page and try again later.',
        ];
    }

    /**
     * Handle a passed validation attempt.
     *
     * @return void
     */
    protected function passedValidation()
    {
        if ($this->has('archiveErrorId')) {
            $this->merge([
                'archiveErrorId' => implode(',', $this->archiveErrorId)
            ]);
        }
    }
}
