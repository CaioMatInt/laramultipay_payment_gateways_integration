<?php

namespace App\Http\Requests\User;

use App\Rules\User\EmailIsVerifiedRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required','email', new EmailIsVerifiedRule()],
            'password' => 'required|string'
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all();

        if ($this->route('provider_name')) {
            $data['provider_name'] = $this->route('provider_name');
        }

        return $data;
    }
}
