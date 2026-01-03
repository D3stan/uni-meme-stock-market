<x-guest>
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold mb-2">Welcome Back</h1>
                <p class="text-gray-400">Log in to continue trading</p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-500/10 border border-green-500/50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <span class="material-icons text-green-500 text-xl">check_circle</span>
                        <p class="flex-1 text-sm text-green-400">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-lg">
                    <div class="flex items-start gap-3">
                        <span class="material-icons text-red-500 text-xl">error</span>
                        <div class="flex-1">
                            <h3 class="font-semibold text-red-500 mb-1">Login Error</h3>
                            <ul class="text-sm text-red-400 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Login Form --}}
            <div class="bg-card rounded-2xl border border-border p-8">
                <form method="POST" action="{{ route('auth.login.post') }}" class="space-y-6">
                    @csrf

                    {{-- Email Field --}}
                    <div>
                        <label for="email" class="block text-sm font-medium mb-2">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 bg-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                            placeholder="mario.rossi@unibo.it"
                        >
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
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="remember" 
                            name="remember" 
                            value="1"
                            class="w-4 h-4 bg-background border-border rounded focus:ring-2 focus:ring-primary"
                        >
                        <label for="remember" class="ml-2 text-sm text-gray-400">
                            Remember me
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit" 
                        class="w-full py-3 bg-primary hover:bg-primary/90 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2"
                    >
                        <span>Log In</span>
                        <span class="material-icons text-xl">arrow_forward</span>
                    </button>
                </form>

                {{-- Register Link --}}
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        Don't have an account?
                        <a href="{{ route('auth.register') }}" class="text-primary hover:text-primary/80 font-medium">
                            Sign up
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-guest>
