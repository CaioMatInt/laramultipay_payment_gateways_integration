<?php

namespace App\Http\Requests\PaymentGatewayKey;

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Enums\PaymentMethod\PaymentMethodEnum;
use App\Models\PaymentGateway;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentGatewayKeyRequest extends FormRequest
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
            'key' => 'required|string',
            'type' => 'nullable|string',
            'payment_gateway_id' => 'required|int|exists:payment_gateways,id',
        ];
    }
}
