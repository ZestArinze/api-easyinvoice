<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchClientRequest extends FormRequest
{
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
       $this->merge([
           // overrides user input
           'business_id' => auth()->user()->business_id,
       ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'             => 'nullable|string',
            'name'              => 'nullable|string',
            'address'           => 'nullable|string',
            'phone_number'      => 'nullable|string',
        ];
    }
}
