<?php

namespace Narolalabs\ErrorLens\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SecurityConfigRequest extends FormRequest
{

    /**
     * Modify requested data before validation
     */
    protected function prepareForValidation()
    {
        $this->merge(['storeRequestedData' => ($this->storeRequestedData) ? '1' : '0']);
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
            'confidentialFieldNames' => 'required_if:storeRequestedData,==,1'
        ];
    }

    public function messages()
    {
        return [
            'confidentialFieldNames.required_if' => 'The confidential field names field is required when store requested data is checked.'
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
        ];
    }
}
