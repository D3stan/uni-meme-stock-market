<x-guest title="Registrati">
    <div class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <img src="{{ asset('icon.png') }}" alt="AlmaStreet" class="h-12 w-12 mx-auto mb-3">
                <h1 class="text-2xl font-bold text-text-main mb-2">Crea il tuo account</h1>
                <p class="text-sm text-text-muted">Usa la tua email istituzionale</p>
            </div>

            <!-- Form Card -->
            <div class="bg-surface-100/50 backdrop-blur-sm rounded-2xl p-6 sm:p-8 border border-surface-200 shadow-xl">
                <form method="POST" action="{{ route('auth.register.post') }}" class="space-y-4">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-text-muted mb-2">
                            Email Istituzionale
                        </label>
                        <x-forms.input
                            type="email"
                            name="email"
                            id="email"
                            placeholder="nome.cognome@studio.unibo.it"
                            icon="email"
                            required
                            :value="old('email')"
                        />
                        <x-forms.validation-error field="email" />
                    </div>

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-text-muted mb-2">
                            Nome Completo
                        </label>
                        <x-forms.input
                            type="text"
                            name="name"
                            id="name"
                            placeholder="Mario Rossi"
                            icon="person"
                            required
                            :value="old('name')"
                        />
                        <x-forms.validation-error field="name" />
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
                            placeholder="Minimo 8 caratteri"
                            icon="lock"
                            required
                        />
                        <x-forms.validation-error field="password" />
                    </div>

                    <!-- Password Confirmation Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-text-muted mb-2">
                            Conferma Password
                        </label>
                        <x-forms.input
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            placeholder="Ripeti la password"
                            icon="lock"
                            required
                        />
                        <x-forms.validation-error field="password_confirmation" />
                    </div>

                    <!-- Submit Button -->
                    <x-forms.button 
                        type="submit" 
                        variant="primary"
                        class="w-full py-3 text-base font-bold rounded-xl mt-6"
                    >
                        Crea Account
                    </x-forms.button>
                </form>
            </div>

            <!-- Footer Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-text-muted">
                    Hai già un account? 
                    <a href="{{ route('auth.login') }}" class="text-brand hover:text-brand-light font-medium transition-colors">
                        Accedi
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest>
