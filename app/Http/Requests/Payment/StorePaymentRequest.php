<?php

namespace App\Http\Requests\Payment;

use App\Enums\Payment\PaymentCurrencyEnum;
use App\Enums\PaymentGateway\PaymentGatewayEnum;
use App\Enums\PaymentMethod\PaymentMethodEnum;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'amount' => 'required|integer',
            'currency' => 'required|string|in:'.implode(',', PaymentCurrencyEnum::values()),
            'payment_method' => 'required|string|in:'.implode(',', PaymentMethodEnum::values()),
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'currency.in' => 'The currency must be one of the following: '.implode(',', PaymentCurrencyEnum::values()),
            'payment_method.in' => 'The payment method must be one of the following: '.implode(',', PaymentMethodEnum::values()),
        ];
    }
}
