<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentLogsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_type' => ['required'],
            'details' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
