<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Allow all users to attempt registration.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define validation rules for new user registration.
     * 
     * Restricts registration to institutional email addresses (@unibo.it or @studio.unibo.it)
     * and enforces password complexity with minimum 8 characters including at least one number.
     *
     * @return array<string, array<int, mixed>>
     */
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

    /**
     * Provide custom English error messages for institutional email and password requirements.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.regex' => 'Only institutional email addresses (@unibo.it or @studio.unibo.it) are allowed.',
            'password.numbers' => 'The password must contain at least one number.',
        ];
    }
}
