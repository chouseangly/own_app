<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupPhoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
         return [
            'phone'        => ['required', 'string', 'max:190'],
            'country_code' => ['required', 'string'],
        ];
    }
}
