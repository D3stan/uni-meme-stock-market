<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:otp_verifications,email'],
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'No verification code found for this email.',
            'code.size' => 'The verification code must be exactly 6 digits.',
            'code.regex' => 'The verification code must contain only numbers.',
        ];
    }
}
