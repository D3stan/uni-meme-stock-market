<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreviewOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'meme_id' => ['required', 'integer', 'exists:memes,id'],
            'type' => ['required', 'string', 'in:buy,sell'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'meme_id.required' => 'Il meme è obbligatorio.',
            'meme_id.exists' => 'Il meme selezionato non esiste.',
            'type.required' => 'Il tipo di operazione è obbligatorio.',
            'type.in' => 'Il tipo di operazione deve essere "buy" o "sell".',
            'quantity.required' => 'La quantità è obbligatoria.',
            'quantity.integer' => 'La quantità deve essere un numero intero.',
            'quantity.min' => 'La quantità deve essere almeno 1.',
        ];
    }
}
