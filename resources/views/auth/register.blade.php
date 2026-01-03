<x-guest>
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">Create Your Account</h1>
                <p class="text-gray-400">Join AlmaStreet and start trading memes</p>
            </div>

            {{-- Error/Success Messages --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <span class="material-icons text-red-500 text-xl">error</span>
                        <div class="flex-1">
                            <h3 class="font-semibold text-red-500 mb-1">Registration Error</h3>
                            <ul class="text-sm text-red-400 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Registration Form --}}
            <div class="bg-card rounded-2xl border border-border p-8">
                <form method="POST" action="{{ route('auth.register.post') }}" class="space-y-6">
                    @csrf

                    {{-- Name Field --}}
                    <div>
                        <label for="name" class="block text-sm font-medium mb-2">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 bg-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            placeholder="Mario Rossi"
                        >
                    </div>

                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Institutional Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 bg-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            placeholder="mario.rossi@unibo.it"
                        >
                        <p class="mt-1 text-xs text-gray-500">Only @unibo.it or @studio.unibo.it emails are allowed</p>
                    </div>

                    {{-- Password Field --}}
                    <div>
                        <label for="password" class="block text-sm font-medium mb-2">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            class="w-full px-4 py-3 bg-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            placeholder="••••••••"
                        >
                        <p class="mt-1 text-xs text-gray-500">Minimum 8 characters with at least one number</p>
                    </div>

                    {{-- Confirm Password Field --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-2">Confirm Password</label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            class="w-full px-4 py-3 bg-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            placeholder="••••••••"
                        >
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit" 
                        class="w-full py-3 bg-primary hover:bg-primary/90 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2"
                    >
                        <span>Create Account</span>
                        <span class="material-icons text-xl">arrow_forward</span>
                    </button>
                </form>

                {{-- Login Link --}}
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        Already have an account?
                        <a href="{{ route('auth.login') }}" class="text-primary hover:text-primary/80 font-medium">
                            Log in
                        </a>
                    </p>
                </div>
            </div>

            {{-- Bonus Badge --}}
            <div class="mt-6 p-4 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-500/30 rounded-xl text-center">
                <div class="flex items-center justify-center gap-2 text-green-400">
                    <span class="material-icons">celebration</span>
                    <span class="text-sm font-medium">Get 100 CFU signup bonus!</span>
                </div>
            </div>

        </div>
    </div>
</x-guest>
