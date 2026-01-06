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
        <!-- Background Chart Graphic -->
        <div class="absolute inset-0 pointer-events-none opacity-20">
            <svg class="w-full h-full" viewBox="0 0 800 400" preserveAspectRatio="none">
                <!-- Simple candlestick-like bars -->
                <line x1="50" y1="300" x2="50" y2="200" stroke="#10b981" stroke-width="2"/>
                <rect x="45" y="220" width="10" height="60" fill="#10b981"/>
                <line x1="100" y1="280" x2="100" y2="180" stroke="#ef4444" stroke-width="2"/>
                <rect x="95" y="230" width="10" height="40" fill="#ef4444"/>
                <line x1="150" y1="320" x2="150" y2="220" stroke="#10b981" stroke-width="2"/>
                <rect x="145" y="240" width="10" height="70" fill="#10b981"/>
                <line x1="200" y1="250" x2="200" y2="150" stroke="#10b981" stroke-width="2"/>
                <rect x="195" y="170" width="10" height="60" fill="#10b981"/>
                <line x1="250" y1="270" x2="250" y2="180" stroke="#ef4444" stroke-width="2"/>
                <rect x="245" y="210" width="10" height="50" fill="#ef4444"/>
                <line x1="300" y1="290" x2="300" y2="190" stroke="#ef4444" stroke-width="2"/>
                <rect x="295" y="230" width="10" height="50" fill="#ef4444"/>
                <line x1="350" y1="310" x2="350" y2="210" stroke="#10b981" stroke-width="2"/>
                <rect x="345" y="230" width="10" height="70" fill="#10b981"/>
                <line x1="400" y1="240" x2="400" y2="140" stroke="#10b981" stroke-width="2"/>
                <rect x="395" y="160" width="10" height="70" fill="#10b981"/>
                <line x1="450" y1="280" x2="450" y2="180" stroke="#ef4444" stroke-width="2"/>
                <rect x="445" y="210" width="10" height="60" fill="#ef4444"/>
                <line x1="500" y1="260" x2="500" y2="160" stroke="#10b981" stroke-width="2"/>
                <rect x="495" y="180" width="10" height="70" fill="#10b981"/>
                <!-- Trend line -->
                <polyline points="50,280 100,260 150,290 200,220 250,250 300,270 350,280 400,200 450,240 500,220" 
                    fill="none" stroke="#10b981" stroke-width="3" opacity="0.3"/>
            </svg>
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
            
            <div class="space-y-3 relative">
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
                
                <!-- Locked Section (Position 4+) -->
                <div class="relative mt-6">
                    <!-- Blurred preview of 4th position -->
                    @if($topMemes->count() >= 4)
                        <div class="blur-sm opacity-40 pointer-events-none">
                            <x-meme.card-compact 
                                mode="landing"
                                :rank="4"
                                :name="$topMemes[3]['name']"
                                :image="$topMemes[3]['image']" 
                                :ticker="$topMemes[3]['ticker']"
                                :price="$topMemes[3]['price']"
                                :change="$topMemes[3]['change']"
                                :volume="$topMemes[3]['volume24h']"
                            />
                        </div>
                    @endif
                    
                    <!-- Lock Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center bg-surface-50/95 backdrop-blur-md px-8 py-8 rounded-2xl border-2 border-surface-200 shadow-xl max-w-sm">
                            <div class="mb-4">
                                <svg class="w-16 h-16 mx-auto text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-text-main mb-2">Market Data Locked</h3>
                            <p class="text-sm text-text-muted mb-6">Join other students trading CFUs.</p>
                            <a href="{{ route('auth.register') }}" class="inline-block">
                                <x-forms.button variant="primary" size="md" class="w-full">
                                    Registrati per sbloccare →
                                </x-forms.button>
                            </a>
                        </div>
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
