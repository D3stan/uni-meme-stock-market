@props([
    'length' => 6,
    'inputClass' => 'w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold bg-surface-50 border-2 border-surface-200 rounded-lg focus:border-brand focus:ring-2 focus:ring-brand/20 outline-none transition-all',
])

{{-- OTP Input Container --}}
<div class="flex justify-center gap-2 mb-6" id="otp-inputs" role="group" aria-label="Inserisci codice OTP">
    @for($i = 0; $i < $length; $i++)
    <input
        type="text"
        maxlength="1"
        pattern="[0-9]"
        inputmode="numeric"
        class="{{ $inputClass }}"
        data-index="{{ $i }}"
        autocomplete="off"
        aria-label="Cifra {{ $i + 1 }}"
    >
    @endfor
</div>

{{-- Hidden input to store complete OTP --}}
<input type="hidden" name="code" id="otp-value">