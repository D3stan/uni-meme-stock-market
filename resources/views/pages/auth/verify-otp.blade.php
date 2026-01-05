<x-guest title="Verifica Email">
    <div class="flex-1 flex items-center justify-center px-4 py-12" data-page="otp-verification">
        <div class="w-full max-w-md">
            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ route('auth.register') }}" class="inline-flex items-center text-sm text-gray-400 hover:text-white transition-colors">
                    <span class="material-icons text-xl mr-1">arrow_back</span>
                    Indietro
                </a>
            </div>

            {{-- Logo & Icon and text --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-green-900/30 rounded-full flex items-center justify-center">
                    <span class="material-icons text-4xl text-green-500">email</span>
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Controlla la tua email</h1>
                <p class="text-sm text-gray-400">
                    Abbiamo inviato un codice a 6 cifre a<br>
                    <span class="text-white font-medium">{{ session('pending_registration.email', $email ?? 'tua email') }}</span>
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    Inserisci il codice per verificare il tuo account
                </p>
            </div>

            {{-- OTP Form --}}
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-2xl p-6 sm:p-8 border border-gray-800 shadow-xl">
                <form method="POST" action="{{ route('auth.verify-otp.post') }}" id="otp-form">
                    @csrf

                    {{-- Hidden email field --}}
                    <input type="hidden" name="email" value="{{ session('pending_registration.email', $email ?? '') }}">

                    {{-- OTP Input Component --}}
                    <x-forms.otp-input />

                    {{-- Error Display --}}
                    <x-forms.validation-error field="code" />
                    <x-forms.validation-error field="email" />
                    <x-forms.validation-error />

                    {{-- Loading State --}}
                    <div id="loading-state" class="hidden text-center mb-4">
                        <div class="inline-flex items-center gap-2 text-sm text-gray-400">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Verifica in corso...</span>
                        </div>
                    </div>

                    {{-- Resend Link --}}
                    <div class="text-center">
                        <p class="text-sm text-gray-400">
                            Non hai ricevuto il codice? 
                            <button type="button" id="resend-btn" class="text-green-500 hover:text-green-400 font-medium transition-colors" data-resend-url="{{ route('auth.verify-otp.show') }}">
                                Invia di nuovo
                            </button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest>
