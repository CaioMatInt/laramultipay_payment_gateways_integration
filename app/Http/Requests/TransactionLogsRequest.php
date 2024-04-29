<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionLogsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'event_type' => ['required'],
            'details' => ['required'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
