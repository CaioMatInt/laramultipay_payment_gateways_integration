<?php

namespace App\Http\Requests\ChargeableItemCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChargeableItemCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:chargeable_item_categories,name,' . $this->route('id'),
        ];
    }
}
