<x-guest>
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary/20 rounded-full mb-4">
                    <span class="material-icons text-primary text-3xl">mail</span>
                </div>
                <h1 class="text-3xl font-bold mb-2">Verify Your Email</h1>
                <p class="text-gray-400">Enter the 6-digit code sent to</p>
                <p class="text-primary font-medium mt-1">{{ $email }}</p>
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
                            <h3 class="font-semibold text-red-500 mb-1">Verification Error</h3>
                            <ul class="text-sm text-red-400 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- OTP Form --}}
            <div class="bg-card rounded-2xl border border-border p-8">
                <form method="POST" action="{{ route('auth.verify-otp.post') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="email" value="{{ $email }}">

                    {{-- OTP Code Field --}}
                    <div>
                        <label for="code" class="block text-sm font-medium mb-2 text-center">Verification Code</label>
                        <input 
                            type="text" 
                            id="code" 
                            name="code" 
                            value="{{ old('code') }}"
                            required
                            autofocus
                            maxlength="6"
                            pattern="[0-9]{6}"
                            class="w-full px-4 py-4 bg-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all text-center text-2xl font-mono tracking-widest"
                            placeholder="000000"
                        >
                        <p class="mt-2 text-xs text-center text-gray-500">Enter the 6-digit code from your email</p>
                    </div>

                    {{-- Development Notice --}}
                    <div class="p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                        <div class="flex items-start gap-2">
                            <span class="material-icons text-yellow-500 text-sm">info</span>
                            <p class="text-xs text-yellow-400">
                                <strong>Dev Mode:</strong> Use code <code class="px-1 py-0.5 bg-yellow-500/20 rounded">123456</code> or check your logs
                            </p>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit" 
                        class="w-full py-3 bg-primary hover:bg-primary/90 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2"
                    >
                        <span>Verify & Complete Registration</span>
                        <span class="material-icons text-xl">check</span>
                    </button>
                </form>

                {{-- Resend Code (Future Feature) --}}
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-400">
                        Didn't receive the code?
                        <button type="button" class="text-primary hover:text-primary/80 font-medium" disabled>
                            Resend
                        </button>
                    </p>
                </div>
            </div>

            {{-- Back Link --}}
            <div class="mt-6 text-center">
                <a href="{{ route('auth.register') }}" class="text-sm text-gray-400 hover:text-white inline-flex items-center gap-1">
                    <span class="material-icons text-sm">arrow_back</span>
                    <span>Back to registration</span>
                </a>
            </div>

        </div>
    </div>
</x-guest>
