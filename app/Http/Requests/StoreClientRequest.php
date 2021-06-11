<?php

namespace App\Http\Requests;

use App\Models\BusinessUser;
use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'business_id' => 'required|integer|exists:businesses,id',
            'phone_number' => ['required', new PhoneNumber],
        ];
    }
}
