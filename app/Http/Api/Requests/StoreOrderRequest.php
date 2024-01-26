<?php

namespace App\Http\Api\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property $currency_iso
 * @property $purchased_amount
 */
class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'currency_iso' => 'required|alpha|size:3|exists:currencies,iso|bail',
            'purchased_amount' => 'required|numeric|gt:0|regex:/^\d{1,6}(\.\d{1,2})?$/|bail',
        ];
    }

    public function messages(): array
    {
        return [
            'purchased_amount.regex' => 'The purchased amount must be a valid numeric value with up to two decimal places.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'currency_iso' => strtoupper($this->currency_iso),
        ]);
    }
}
