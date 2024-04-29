<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentGatewayRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['required'],
            'slug' => ['required'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
