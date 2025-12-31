@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        
        {{-- Chart Background Image Card --}}
        <div class="relative mb-8 rounded-2xl overflow-hidden bg-gradient-to-b from-gray-900 to-gray-950 border border-gray-800/50">
            {{-- SVG Chart Background with Arrow --}}
            <svg class="w-full h-48 sm:h-56" viewBox="0 0 400 200" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                {{-- Dark gradient background --}}
                <defs>
                    <linearGradient id="chartGradient" x1="0%" y1="100%" x2="0%" y2="0%">
                        <stop offset="0%" style="stop-color:#064e3b;stop-opacity:0.3" />
                        <stop offset="100%" style="stop-color:#10b981;stop-opacity:0.1" />
                    </linearGradient>
                    <linearGradient id="arrowGradient" x1="0%" y1="100%" x2="100%" y2="0%">
                        <stop offset="0%" style="stop-color:#10b981;stop-opacity:0.6" />
                        <stop offset="100%" style="stop-color:#10b981;stop-opacity:1" />
                    </linearGradient>
                    <filter id="glow" x="-50%" y="-50%" width="200%" height="200%">
                        <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                        <feMerge>
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
                
                {{-- Bar chart in background --}}
                <g fill="url(#chartGradient)" opacity="0.8">
                    <rect x="40" y="150" width="25" height="30" rx="2" />
                    <rect x="75" y="140" width="25" height="40" rx="2" />
                    <rect x="110" y="125" width="25" height="55" rx="2" />
                    <rect x="145" y="130" width="25" height="50" rx="2" />
                    <rect x="180" y="110" width="25" height="70" rx="2" />
                    <rect x="215" y="95" width="25" height="85" rx="2" />
                    <rect x="250" y="75" width="25" height="105" rx="2" />
                    <rect x="285" y="55" width="25" height="125" rx="2" />
                    <rect x="320" y="40" width="25" height="140" rx="2" />
                </g>
                
                {{-- Glowing upward arrow curve --}}
                <path 
                    d="M 50 160 Q 120 150, 180 120 Q 240 90, 300 50 L 340 20" 
                    stroke="url(#arrowGradient)" 
                    stroke-width="4" 
                    fill="none" 
                    stroke-linecap="round"
                    filter="url(#glow)"
                />
                
                {{-- Arrow head --}}
                <polygon 
                    points="335,35 355,10 340,30" 
                    fill="#10b981" 
                    filter="url(#glow)"
                    transform="rotate(-25 345 22)"
                />
            </svg>
        </div>

        {{-- Title Section --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-2">
                Bentornato su
            </h1>
            <h2 class="text-3xl sm:text-4xl font-bold text-emerald-500">
                AlmaStreet
            </h2>
            <p class="mt-3 text-gray-400 text-base">
                Fai trading di meme accademici come un pro.
            </p>
        </div>

        {{-- Login Form --}}
        <form id="login-form" class="space-y-5" action="{{ route('login') }}" method="POST">
            @csrf

            {{-- Email Field --}}
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-white">
                    Email
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="bg-gray-900/80 border border-gray-700 text-white text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block w-full pl-12 pr-4 py-4 placeholder-gray-500"
                        placeholder="Email universitaria" 
                        required
                        autocomplete="email"
                    >
                </div>
                <p id="email-error" class="mt-2 text-sm text-red-500 hidden"></p>
            </div>

            {{-- Password Field --}}
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-white">
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
                        placeholder="Password" 
                        required
                        autocomplete="current-password"
                    >
                    <button 
                        type="button" 
                        id="toggle-password"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 hover:text-gray-300 transition-colors"
                    >
                        <svg id="eye-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="eye-closed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                <p id="password-error" class="mt-2 text-sm text-red-500 hidden"></p>
            </div>

            {{-- Forgot Password Link --}}
            <div class="flex justify-end">
                <a href="#" class="text-sm text-emerald-500 hover:text-emerald-400 transition-colors">
                    Password dimenticata?
                </a>
            </div>

            {{-- Submit Button --}}
            <div class="flex items-center gap-3 pt-2">
                
                {{-- Main Submit Button --}}
                <button 
                    type="submit" 
                    id="submit-btn"
                    class="flex-1 h-14 text-gray-900 bg-emerald-500 hover:bg-emerald-400 focus:ring-4 focus:ring-emerald-500/50 font-bold rounded-xl text-lg transition-all duration-200 flex items-center justify-center gap-2"
                >
                    <span id="btn-text">Accedi</span>
                    <svg id="btn-spinner" class="animate-spin h-5 w-5 text-gray-900 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>

        {{-- Register Link --}}
        <div class="mt-8 pt-6 border-t border-gray-800">
            <p class="text-center text-gray-400">
                Non hai un account? 
                <a href="{{ route('register.page') }}" class="text-emerald-500 hover:text-emerald-400 font-medium transition-colors">
                    Registrati
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
    const form = document.getElementById('login-form');
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    const errorToast = document.getElementById('error-toast');
    const errorMessage = document.getElementById('error-message');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        eyeOpen.classList.toggle('hidden');
        eyeClosed.classList.toggle('hidden');
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.textContent = 'Accesso in corso...';
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
                    password: formData.get('password')
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Credenziali non valide');
            }

            // Success - redirect to marketplace
            window.location.href = data.redirect || '/marketplace';

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
        btnText.textContent = 'Accedi';
        btnSpinner.classList.add('hidden');
    }
});
</script>
@endsection
