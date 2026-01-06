<x-guest title="Il mercato dei meme universitari">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-40 bg-surface-50/80 backdrop-blur-md border-b border-surface-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <img src="{{ asset('icon.png') }}" alt="AlmaStreet" class="h-8 w-8">
                    <span class="text-xl font-bold text-text-main">University Exchange</span>
                </div>
                
                <!-- Login Button -->
                <a href="{{ route('auth.login') }}" class="text-sm font-medium text-text-main hover:text-brand transition-colors px-4 py-2 rounded-lg hover:bg-surface-100">
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="relative pt-24 pb-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Background Chart Image -->
        <div class="absolute inset-0 pointer-events-none opacity-10">
            <img src="{{ asset('storage/test/placeholder.png') }}" alt="" class="w-full h-full object-cover">
        </div>
        
        <div class="relative max-w-4xl mx-auto text-center">
            <!-- Market Status Badge -->
            <div class="inline-flex items-center gap-2 bg-brand/10 border border-brand/20 rounded-full px-4 py-2 mb-6">
                <span class="flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-brand opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                </span>
                <span class="text-xs font-bold text-brand uppercase tracking-wide">Market Open</span>
            </div>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-text-main mb-4 leading-tight">
                Il mercato dei Meme<br>
                <span class="text-brand">è aperto.</span>
            </h1>
            
            <p class="text-lg sm:text-xl text-text-muted mb-8 max-w-2xl mx-auto">
                Scambia i tuoi CFU contro l'AMM e domina la classifica universitaria.
            </p>
            
            <!-- CTA Button -->
            <div class="mb-12">
                <a href="{{ route('auth.register') }}" class="inline-block w-full sm:w-auto">
                    <x-forms.button variant="primary" size="lg" class="w-full sm:w-auto px-12 py-4 text-base font-bold rounded-xl shadow-lg shadow-brand/20 hover:shadow-xl hover:shadow-brand/30 transition-all">
                        Inizia a fare Trading
                    </x-forms.button>
                </a>
                <p class="text-sm text-text-muted mt-3">
                    Registrati con la tua email istituzionale • 100 CFU gratis
                </p>
            </div>
        </div>
    </div>

    <!-- Market Teaser Section (Paywall Effect) -->
    <div class="px-4 sm:px-6 lg:px-8 pb-16">
        <div class="max-w-4xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-text-main flex items-center gap-2">
                    Top Movers 🔥
                </h2>
                <span class="text-sm text-text-muted">Vol. 24h</span>
            </div>
            
            <div class="space-y-3">
                <!-- Top 3 Memes (Fully Visible) -->
                @foreach($topMemes->take(3) as $index => $meme)
                    <x-meme.card-compact 
                        mode="landing"
                        :rank="$index + 1"
                        :name="$meme['name']"
                        :image="$meme['image']" 
                        :ticker="$meme['ticker']"
                        :price="$meme['price']"
                        :change="$meme['change']"
                        :volume="$meme['volume24h']"
                    />
                @endforeach
                
                <!-- Locked Section -->
                <div class="relative">
                    <!-- Lock Icon and Message -->
                    <div class="flex items-center justify-center py-8">
                        <svg class="w-12 h-12 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    
                    <div class="text-center mb-6">
                        <h3 class="text-lg font-bold text-text-main mb-1">Market Data Locked</h3>
                        <p class="text-sm text-text-muted mb-4">Join other students trading CFUs.</p>
                    </div>
                    
                    <!-- CTA Button -->
                    <div class="flex justify-center">
                        <a href="{{ route('auth.register') }}" class="inline-block w-full max-w-md">
                            <x-forms.button variant="primary" size="md" class="w-full">
                                Registrati per sbloccare →
                            </x-forms.button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-auto border-t border-surface-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-text-muted">
                © 2026 AlmaStreet • Il trading è virtuale e a scopo ludico
            </p>
        </div>
    </footer>
</x-guest>
