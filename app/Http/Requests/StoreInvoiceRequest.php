<?php

namespace App\Http\Requests;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvoiceRequest extends FormRequest
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

        $currency = Currency::first();
        $this->merge([
            // @TODO add default currncy in Settings
            'currency_id' => $this->currency_id ?? $currency ? $currency->id : null,
            'status' => $this->status ?? config('data.invoice_statuses')[0], // value?
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
            'summary' => 'nullable|string|max:1000',
            'vat' => 'numeric|min:0|max:100',
            'total_paid' => 'numeric|min:0',
            'status' => ['string', Rule::in(config('data.invoice_statuses'))],
            'due_date' => 'nullable|date_format:Y-m-d',
            'currency_id' => 'required|integer|exists:clients,id',
            'client_id' => 'required|integer|exists:clients,id',

            'invoice_items'                    => 'required|array',
            'invoice_items.*.item'               => 'required|string|max:500',
            'invoice_items.*.quantity'           => 'required|numeric|min:0',
            'invoice_items.*.unit_price'         => 'required|numeric|min:0',
            'invoice_items.*.discount'           => 'numeric|min:0',
            'invoice_items.*.description'        => 'nullable|string|max:500',
        ];
    }
}
