<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer'],
            'currency' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
