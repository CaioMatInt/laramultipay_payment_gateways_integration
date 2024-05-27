<?php

namespace App\Http\Requests\ChargeableItemPrice;

use App\Services\ChargeableItem\ChargeableItemService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ShowChargeableItemPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'id' => 'required|integer',
            'chargeable_item_id' => [
                'required',
                'integer',
                Rule::exists('chargeable_items', 'id')->where(function ($query) {
                    $query->where('company_id', $this->user()->company_id);
                }),
            ],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'chargeable_item_id' => $this->route('chargeable_item_id'),
            'id' => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'chargeable_item_id.required' => 'Chargeable item id parameter is required',
            'chargeable_item_id.integer' => 'Chargeable item id parameter must be an integer',
            'chargeable_item_id.exists' => 'The chargeable item does not exist',
        ];
    }
}
