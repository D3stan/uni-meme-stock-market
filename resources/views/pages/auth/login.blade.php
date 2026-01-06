<x-guest title="Accedi">
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('icon.png') }}" alt="AlmaStreet" class="h-12 w-12 mx-auto mb-3">
                <h1 class="text-2xl font-bold text-text-main mb-2">Bentornato su AlmaStreet</h1>
                <p class="text-sm text-text-muted">Accedi per continuare a fare trading</p>
            </div>

            <!-- Form Card -->
            <div class="bg-surface-100/50 backdrop-blur-sm rounded-2xl p-6 sm:p-8 border border-surface-200 shadow-xl">
                <form method="POST" action="{{ route('auth.login.post') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-text-muted mb-2">
                            Email
                        </label>
                        <x-forms.input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="tua.email@studio.unibo.it"
                            icon="email"
                            required
                            :value="old('email')"
                        />
                        <x-forms.validation-error field="email" />
                        <div id="email-forgot-error" class="hidden text-brand-danger text-sm mt-1">
                            Per favore inserisci la tua email per richiedere il reset della password.
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-text-muted mb-2">
                            Password
                        </label>
                        <x-forms.input
                            type="password"
                            name="password"
                            id="password"
                            placeholder="La tua password"
                            icon="lock"
                            required
                        />
                        <x-forms.validation-error field="password" />
                        <div class="text-right mt-2">
                            <a href="#" id="forgot-password-link" class="text-sm text-brand hover:text-brand-light font-medium transition-colors">
                                Password dimenticata?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <x-forms.button 
                        type="submit" 
                        variant="primary"
                        class="w-full py-3 text-base font-bold rounded-xl mt-6"
                    >
                        Accedi
                    </x-forms.button>
                </form>
            </div>

            <!-- Footer Links -->
            <div class="text-center mt-6">
                <p class="text-sm text-text-muted">
                    Non hai un account? 
                    <a href="{{ route('auth.register') }}" class="text-brand hover:text-brand-light font-medium transition-colors">
                        Registrati
                    </a>
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('forgot-password-link').addEventListener('click', function(e) {
            e.preventDefault();
            const emailInput = document.getElementById('email');
            const email = emailInput.value.trim();
            const errorDiv = document.getElementById('email-forgot-error');
            
            if (!email) {
                errorDiv.classList.remove('hidden');
                emailInput.focus();
                emailInput.classList.add('border-brand-danger');
                return;
            }
            
            // Hide error and reset border
            errorDiv.classList.add('hidden');
            emailInput.classList.remove('border-brand-danger');
            
            // Send forgot password request
            fetch('{{ route('auth.forgot-password.post') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Redirect to OTP page with email
                    window.location.href = '{{ route('auth.verify-otp.show') }}';
                } else {
                    errorDiv.textContent = data.message || 'Errore durante l\'invio dell\'email.';
                    errorDiv.classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                errorDiv.textContent = 'Errore durante l\'invio della richiesta.';
                errorDiv.classList.remove('hidden');
            });
        });

        // Hide error when user starts typing
        document.getElementById('email').addEventListener('input', function() {
            const errorDiv = document.getElementById('email-forgot-error');
            errorDiv.classList.add('hidden');
            this.classList.remove('border-brand-danger');
        });
    </script>
</x-guest>
