<x-guest title="Verifica Email">
    <div class="flex-1 flex items-center justify-center px-4 py-12" data-page="otp-verification">
        <div class="w-full max-w-md">
            {{-- Back Button --}}
            <div class="mb-6">
                <a href="{{ session('pending_password_reset') ? route('auth.login') : route('auth.register') }}" class="inline-flex items-center text-sm text-text-muted hover:text-text-main transition-colors">
                    <span class="material-icons text-xl mr-1">arrow_back</span>
                    Indietro
                </a>
            </div>

            {{-- Logo & Icon and text --}}
            <div class="text-center mb-8">
                <div class="w-16 h-16 mx-auto mb-4 bg-brand/20 rounded-full flex items-center justify-center">
                    <span class="material-icons text-4xl text-brand">email</span>
                </div>
                <h1 class="text-2xl font-bold text-text-main mb-2">Controlla la tua email</h1>
                <p class="text-sm text-text-muted">
                    Abbiamo inviato un codice a 6 cifre a<br>
                    <span class="text-text-main font-medium">{{ session('pending_registration.email', session('pending_password_reset.email', $email ?? 'tua email')) }}</span>
                </p>
                <p class="text-xs text-text-muted mt-2">
                    Inserisci il codice per {{ session('pending_password_reset') ? 'resettare la tua password' : 'verificare il tuo account' }}
                </p>
            </div>

            {{-- OTP Form --}}
            <div class="bg-surface-100/50 backdrop-blur-sm rounded-2xl p-6 sm:p-8 border border-surface-200 shadow-xl">
                <form method="POST" action="{{ route('auth.verify-otp.post') }}" id="otp-form">
                    @csrf

                    {{-- Hidden email field --}}
                    <input type="hidden" name="email" value="{{ session('pending_registration.email', session('pending_password_reset.email', $email ?? '')) }}">

                    {{-- OTP Input Component --}}
                    <x-forms.otp-input />

                    {{-- Error Display --}}
                    <x-forms.validation-error field="code" />
                    <x-forms.validation-error field="email" />

                    {{-- Loading State --}}
                    <div id="loading-state" class="hidden text-center mb-4">
                        <div class="inline-flex items-center gap-2 text-sm text-text-muted">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span>Verifica in corso...</span>
                        </div>
                    </div>

                    {{-- Resend Link --}}
                    <div class="text-center">
                        <p class="text-sm text-text-muted">
                            Non hai ricevuto il codice? 
                            <button type="button" id="resend-btn" class="text-brand hover:text-brand-light font-medium transition-colors" data-resend-url="{{ route('auth.verify-otp.show') }}">
                                Invia di nuovo
                            </button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest>
