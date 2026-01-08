<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PreviewOrderRequest extends FormRequest
{
    /**
     * Authorize only authenticated users to preview trading orders.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Define validation constraints for previewing order calculations.
     * 
     * Similar to ExecuteOrderRequest but without expected_total,
     * as the preview generates the total cost/revenue estimate.
     *
     * @return array<string, array<int, string>>
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
     * Provide localized Italian error messages for validation failures.
     *
     * @return array<string, string>
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
