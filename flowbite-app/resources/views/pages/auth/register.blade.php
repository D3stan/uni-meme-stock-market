@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col px-4 py-6 sm:px-6 lg:px-8">
    <div class="w-full max-w-md mx-auto">
        
        {{-- Header with Back Button and Progress Dots --}}
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('home') }}" class="p-2 -ml-2 text-gray-400 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            
            {{-- Progress Dots --}}
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="w-2 h-2 rounded-full bg-gray-600"></span>
                <span class="w-2 h-2 rounded-full bg-gray-600"></span>
            </div>
            
            {{-- Spacer for centering --}}
            <div class="w-10"></div>
        </div>

        {{-- Bonus Badge --}}
        <div class="mb-6">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/30 rounded-full text-emerald-500 text-sm font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                100 CFU SIGNUP BONUS
            </span>
        </div>

        {{-- Title Section --}}
        <div class="mb-6">
            <h1 class="text-4xl sm:text-5xl font-bold text-white mb-1">
                Unisciti all'
            </h1>
            <h2 class="text-4xl sm:text-5xl font-bold text-emerald-500 mb-4">
                Exchange
            </h2>
            <p class="text-gray-400 text-base leading-relaxed">
                Fai trading sui meme. Ottieni crediti formativi. Verifica il tuo status di studente per iniziare a battere il mercato.
            </p>
        </div>

        {{-- Registration Form --}}
        <form id="register-form" class="space-y-5 mt-8" action="{{ route('register') }}" method="POST">
            @csrf

            {{-- University Email Field --}}
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-300">
                    Email Universitaria
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="bg-gray-900/80 border border-gray-700 text-white text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-12 pr-4 py-4 placeholder-gray-500"
                        placeholder="nome.cognome@studio.unibo.it" 
                        required
                        autocomplete="email"
                    >
                </div>
                <p id="email-error" class="mt-2 text-sm text-red-500 hidden"></p>
            </div>

            {{-- Password Field --}}
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-300">
                    Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="bg-gray-900/80 border border-gray-700 text-white text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-12 pr-12 py-4 placeholder-gray-500"
                        placeholder="Utilizza una password forte" 
                        required
                        autocomplete="new-password"
                        minlength="8"
                    >
                    <button 
                        type="button" 
                        id="toggle-password"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-300 transition-colors"
                    >
                        <svg id="eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Confirm Password Field --}}
            <div>
                <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-300">
                    Conferma Password
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="bg-gray-900/80 border border-gray-700 text-white text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-12 pr-4 py-4 placeholder-gray-500"
                        placeholder="Ripeti la password" 
                        required
                        autocomplete="new-password"
                    >
                </div>
                <p id="password-error" class="mt-2 text-sm text-red-500 hidden"></p>
            </div>

            {{-- Password Requirements --}}
            <div class="flex items-center gap-2 text-sm">
                <svg id="length-check" class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span id="length-text" class="text-gray-500">Deve contenere almeno 8 caratteri</span>
            </div>

            {{-- Submit Button --}}
            <div class="pt-6">
                <button 
                    type="submit" 
                    id="submit-btn"
                    class="w-full h-14 text-gray-900 bg-emerald-500 hover:bg-emerald-400 focus:ring-4 focus:ring-emerald-500/50 font-bold rounded-full text-lg transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <span id="btn-text">Verifica e richiedi il bonus</span>
                    <svg id="btn-arrow" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                    <svg id="btn-spinner" class="animate-spin h-5 w-5 text-gray-900 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>

        {{-- Login Link --}}
        <div class="mt-8 pt-6 border-t border-gray-800">
            <p class="text-center text-gray-400">
                Hai già un account? 
                <a href="{{ route('login.page') }}" class="text-emerald-500 hover:text-emerald-400 font-medium transition-colors">
                    Log in
                </a>
            </p>
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
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('register-form');
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnArrow = document.getElementById('btn-arrow');
    const btnSpinner = document.getElementById('btn-spinner');
    const errorToast = document.getElementById('error-toast');
    const errorMessage = document.getElementById('error-message');
    const lengthCheck = document.getElementById('length-check');
    const lengthText = document.getElementById('length-text');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        confirmInput.setAttribute('type', type);
        eyeOpen.classList.toggle('hidden');
        eyeClosed.classList.toggle('hidden');
    });

    // Real-time password validation
    passwordInput.addEventListener('input', function() {
        const isValid = passwordInput.value.length >= 8;
        if (isValid) {
            lengthCheck.classList.remove('text-gray-500');
            lengthCheck.classList.add('text-emerald-500');
            lengthText.classList.remove('text-gray-500');
            lengthText.classList.add('text-emerald-500');
        } else {
            lengthCheck.classList.add('text-gray-500');
            lengthCheck.classList.remove('text-emerald-500');
            lengthText.classList.add('text-gray-500');
            lengthText.classList.remove('text-emerald-500');
        }
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate passwords match
        if (passwordInput.value !== confirmInput.value) {
            showError('Le password non corrispondono');
            return;
        }

        // Validate password length
        if (passwordInput.value.length < 8) {
            showError('La password deve essere di almeno 8 caratteri');
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Creazione in corso...';
        btnArrow.classList.add('hidden');
        btnSpinner.classList.remove('hidden');

        // Clear previous errors
        hideError();

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: formData.get('email'),
                    password: formData.get('password'),
                    password_confirmation: formData.get('password_confirmation')
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Errore durante la registrazione');
            }

            // Success - redirect to OTP verification
            window.location.href = data.redirect || '/verify-otp?email=' + encodeURIComponent(formData.get('email'));

        } catch (error) {
            showError(error.message);
            resetButton();
        }
    });

    function showError(message) {
        errorMessage.textContent = message;
        errorToast.classList.remove('hidden');
        setTimeout(() => {
            errorToast.classList.add('hidden');
        }, 5000);
    }

    function hideError() {
        errorToast.classList.add('hidden');
    }

    function resetButton() {
        submitBtn.disabled = false;
        btnText.textContent = 'Verify & Claim Bonus';
        btnArrow.classList.remove('hidden');
        btnSpinner.classList.add('hidden');
    }
});
</script>
@endsection
