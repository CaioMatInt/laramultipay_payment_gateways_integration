<?php

namespace App\Http\Requests\ChargeableItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChargeableItemRequest extends FormRequest
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
                'sometimes',
                'string',
                'max:255',
                Rule::unique('chargeable_items')->where(function ($query) {
                    return $query->where('company_id', auth()->user()->company_id);
                })->ignore($this->route('chargeable_item')),
            ],
            'description' => 'sometimes|string',
            'chargeable_item_category_id' => 'sometimes|exists:chargeable_item_categories,id,company_id,'
                . auth()->user()->company_id,
        ];
    }
}
