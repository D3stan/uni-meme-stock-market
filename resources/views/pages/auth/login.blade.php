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
            <div class="text-center mt-6 space-y-2">
                <p class="text-sm text-text-muted">
                    Non hai un account? 
                    <a href="{{ route('auth.register') }}" class="text-brand hover:text-brand-light font-medium transition-colors">
                        Registrati
                    </a>
                </p>
                <p class="text-sm">
                    <a href="#" class="text-text-muted hover:text-text-main transition-colors">
                        Password dimenticata?
                    </a>
                </p>
            </div>
        </div>
    </div>
</x-guest>
