<?php

namespace App\Http\Requests\ChargeableItem;

use App\Enums\Payment\PaymentCurrencyEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreChargeableItemRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('chargeable_items')->where(function ($query) {
                    return $query->where('company_id', auth()->user()->company_id);
                }),
            ],
            'description' => 'required|string',
            'chargeable_item_category_id' => 'required|exists:chargeable_item_categories,id,company_id,'
                . auth()->user()->company_id,
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
