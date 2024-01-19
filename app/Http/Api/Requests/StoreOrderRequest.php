<?php

namespace App\Http\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property $currency_iso
 * @property $purchased_amount
 */
//TODO: Prettify the regex expression error messages
class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'currency_iso' => 'required|alpha|size:3|exists:currencies,iso|bail',
            'purchased_amount' => 'required|numeric|gt:0|regex:/^\d{1,6}(\.\d{1,2})?$/|bail',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'currency_iso' => strtoupper($this->currency_iso),
        ]);
    }
}
