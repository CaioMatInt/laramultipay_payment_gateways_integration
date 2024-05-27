<?php

namespace App\Http\Requests\ChargeableItemPrice;

use App\Enums\Payment\PaymentCurrencyEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChargeableItemPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' => 'required|integer',
            'currency' => 'required|string|in:'.implode(',', PaymentCurrencyEnum::values()),
            'chargeable_item_id' => [
                'required',
                'integer',
                Rule::exists('chargeable_items', 'id')->where(function ($query) {
                    return $query->where('company_id', optional(auth()->user())->company_id);
                }),
            ]
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'currency.in' => 'The currency must be one of the following: '.implode(',', PaymentCurrencyEnum::values()),
        ];
    }
}
