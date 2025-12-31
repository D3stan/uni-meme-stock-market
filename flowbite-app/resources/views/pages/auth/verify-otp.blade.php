@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col px-4 py-6 sm:px-6 lg:px-8">
    <div class="w-full max-w-md mx-auto">
        
        {{-- Header with Back Button and Logo --}}
        <div class="flex items-center justify-between mb-12">
            <a href="{{ route('register.page') }}" class="p-2 -ml-2 text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            
            {{-- Logo --}}
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <span class="text-white font-bold">AlmaStreet</span>
            </div>
            
            {{-- Spacer for centering --}}
            <div class="w-10"></div>
        </div>

        {{-- Email Icon Circle --}}
        <div class="flex justify-center mb-8">
            <div class="w-24 h-24 rounded-full bg-gray-800/80 border border-gray-700 flex items-center justify-center">
                <svg class="w-12 h-12 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                </svg>
            </div>
        </div>

        {{-- Title Section --}}
        <div class="text-center mb-10">
            <h1 class="text-xl sm:text-2xl text-gray-300 mb-2">
                Abbiamo inviato un codice a
            </h1>
            <p class="text-xl sm:text-2xl font-bold text-white" id="user-email">
                {{ request('email', 'student@unibo.it') }}
            </p>
            <p class="mt-4 text-gray-500 text-sm">
                Inserisci il codice per verificare il tuo account
            </p>
        </div>

        {{-- OTP Input --}}
        <form id="otp-form" class="mb-8">
            @csrf
            <input type="hidden" name="email" value="{{ request('email', '') }}">
            
            <div class="flex justify-center gap-2 sm:gap-3" id="otp-container">
                <input 
                    type="text" 
                    maxlength="1" 
                    class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold bg-gray-900/80 border-2 border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    data-index="0"
                    inputmode="numeric"
                    pattern="[0-9]"
                    autocomplete="one-time-code"
                >
                <input 
                    type="text" 
                    maxlength="1" 
                    class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold bg-gray-900/80 border-2 border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    data-index="1"
                    inputmode="numeric"
                    pattern="[0-9]"
                >
                <input 
                    type="text" 
                    maxlength="1" 
                    class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold bg-gray-900/80 border-2 border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    data-index="2"
                    inputmode="numeric"
                    pattern="[0-9]"
                >
                <input 
                    type="text" 
                    maxlength="1" 
                    class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold bg-gray-900/80 border-2 border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    data-index="3"
                    inputmode="numeric"
                    pattern="[0-9]"
                >
                <input 
                    type="text" 
                    maxlength="1" 
                    class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold bg-gray-900/80 border-2 border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    data-index="4"
                    inputmode="numeric"
                    pattern="[0-9]"
                >
                <input 
                    type="text" 
                    maxlength="1" 
                    class="otp-input w-12 h-14 sm:w-14 sm:h-16 text-center text-2xl font-bold bg-gray-900/80 border-2 border-gray-700 text-white rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                    data-index="5"
                    inputmode="numeric"
                    pattern="[0-9]"
                >
            </div>

            {{-- Loading Spinner (hidden by default) --}}
            <div id="verify-spinner" class="hidden flex justify-center mt-6">
                <svg class="animate-spin h-8 w-8 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            {{-- Success Checkmark (hidden by default) --}}
            <div id="success-check" class="hidden flex justify-center mt-6">
                <div class="w-16 h-16 rounded-full bg-emerald-500/20 flex items-center justify-center animate-pulse">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </form>

        {{-- Resend Code Section --}}
        <div class="text-center">
            <p class="text-gray-500 text-sm mb-2">
                Non hai ricevuto il codice?
            </p>
            <div class="flex items-center justify-center gap-3">
                <button 
                    type="button" 
                    id="resend-btn"
                    class="text-emerald-500 hover:text-emerald-400 font-medium text-sm transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled
                >
                    Invia di nuovo
                </button>
                <span id="countdown" class="text-xs font-mono bg-emerald-500/10 text-emerald-500 px-2 py-1 rounded-md">
                    00:30
                </span>
            </div>
        </div>

        {{-- Error Toast --}}
        <div id="error-toast" class="fixed top-4 right-4 z-50 hidden">
            <div class="flex items-center p-4 bg-red-900/90 border border-red-700 rounded-xl shadow-lg backdrop-blur-sm">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <span id="error-message" class="text-sm text-white"></span>
            </div>
        </div>

        {{-- Success Toast --}}
        <div id="success-toast" class="fixed top-4 right-4 z-50 hidden">
            <div class="flex items-center p-4 bg-emerald-900/90 border border-emerald-700 rounded-xl shadow-lg backdrop-blur-sm">
                <svg class="w-5 h-5 text-emerald-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span id="success-message" class="text-sm text-white"></span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const form = document.getElementById('otp-form');
    const verifySpinner = document.getElementById('verify-spinner');
    const successCheck = document.getElementById('success-check');
    const resendBtn = document.getElementById('resend-btn');
    const countdownEl = document.getElementById('countdown');
    const errorToast = document.getElementById('error-toast');
    const errorMessage = document.getElementById('error-message');
    const successToast = document.getElementById('success-toast');
    const successMessage = document.getElementById('success-message');

    let countdownTimer;
    let countdownSeconds = 30;

    // Start countdown on page load
    startCountdown();

    // OTP input handling
    otpInputs.forEach((input, index) => {
        // Focus first input on load
        if (index === 0) input.focus();

        // Handle input
        input.addEventListener('input', function(e) {
            // Only allow numbers
            this.value = this.value.replace(/[^0-9]/g, '');

            if (this.value.length === 1) {
                // Move to next input
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                
                // Check if all inputs are filled
                const code = getOtpCode();
                if (code.length === 6) {
                    verifyOtp(code);
                }
            }
        });

        // Handle backspace
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = '';
            }
        });

        // Handle paste
        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedData = e.clipboardData.getData('text').replace(/[^0-9]/g, '').slice(0, 6);
            
            pastedData.split('').forEach((char, i) => {
                if (otpInputs[i]) {
                    otpInputs[i].value = char;
                }
            });

            // Focus appropriate input
            const lastFilledIndex = Math.min(pastedData.length, otpInputs.length) - 1;
            if (lastFilledIndex >= 0 && lastFilledIndex < otpInputs.length - 1) {
                otpInputs[lastFilledIndex + 1].focus();
            }

            // Auto-submit if complete
            if (pastedData.length === 6) {
                verifyOtp(pastedData);
            }
        });
    });

    // Resend button
    resendBtn.addEventListener('click', async function() {
        if (resendBtn.disabled) return;

        resendBtn.disabled = true;
        countdownSeconds = 30;
        startCountdown();

        try {
            const email = document.querySelector('input[name="email"]').value;
            const response = await fetch('/api/resend-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Errore durante l\'invio');
            }

            showSuccess('Codice inviato nuovamente!');

        } catch (error) {
            showError(error.message);
        }
    });

    function getOtpCode() {
        return Array.from(otpInputs).map(input => input.value).join('');
    }

    async function verifyOtp(code) {
        // Show loading state
        verifySpinner.classList.remove('hidden');
        otpInputs.forEach(input => input.disabled = true);

        try {
            const email = document.querySelector('input[name="email"]').value;
            const response = await fetch('/api/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ email, otp: code })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Codice non valido');
            }

            // Show success state
            verifySpinner.classList.add('hidden');
            successCheck.classList.remove('hidden');
            otpInputs.forEach(input => {
                input.classList.remove('border-gray-700');
                input.classList.add('border-emerald-500', 'bg-emerald-500/10');
            });

            // Redirect after animation
            setTimeout(() => {
                window.location.href = data.redirect || '/onboarding';
            }, 1500);

        } catch (error) {
            // Show error state
            verifySpinner.classList.add('hidden');
            otpInputs.forEach(input => {
                input.disabled = false;
                input.value = '';
                input.classList.remove('border-gray-700');
                input.classList.add('border-red-500');
            });
            otpInputs[0].focus();

            showError(error.message);

            // Reset border color after delay
            setTimeout(() => {
                otpInputs.forEach(input => {
                    input.classList.remove('border-red-500');
                    input.classList.add('border-gray-700');
                });
            }, 2000);
        }
    }

    function startCountdown() {
        clearInterval(countdownTimer);
        updateCountdownDisplay();

        countdownTimer = setInterval(() => {
            countdownSeconds--;
            updateCountdownDisplay();

            if (countdownSeconds <= 0) {
                clearInterval(countdownTimer);
                resendBtn.disabled = false;
                countdownEl.classList.add('hidden');
            }
        }, 1000);
    }

    function updateCountdownDisplay() {
        const mins = Math.floor(countdownSeconds / 60).toString().padStart(2, '0');
        const secs = (countdownSeconds % 60).toString().padStart(2, '0');
        countdownEl.textContent = `${mins}:${secs}`;
        countdownEl.classList.remove('hidden');
    }

    function showError(message) {
        errorMessage.textContent = message;
        errorToast.classList.remove('hidden');
        setTimeout(() => {
            errorToast.classList.add('hidden');
        }, 5000);
    }

    function showSuccess(message) {
        successMessage.textContent = message;
        successToast.classList.remove('hidden');
        setTimeout(() => {
            successToast.classList.add('hidden');
        }, 3000);
    }
});
</script>
@endsection
