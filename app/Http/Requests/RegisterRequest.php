<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users',
                'regex:/^[a-zA-Z0-9._%+-]+@(unibo\.it|studio\.unibo\.it)$/'
            ],
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)->numbers(),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email.regex' => 'Only institutional email addresses (@unibo.it or @studio.unibo.it) are allowed.',
            'password.numbers' => 'The password must contain at least one number.',
        ];
    }
}
