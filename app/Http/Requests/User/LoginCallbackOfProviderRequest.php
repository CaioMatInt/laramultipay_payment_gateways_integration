<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class LoginCallbackOfProviderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'provider_name' => ['in:' . implode(',', config('auth.third_party_login_providers'))]
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();
        $data['provider_name'] = $this->route('provider_name');

        return $data;
    }
}
