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
    <div class="relative pt-24 pb-1 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Background Chart Image with Gradient Fade -->
        <div class="absolute inset-0 pointer-events-none">
            <img src="{{ asset('landing-background.png') }}" alt="" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-surface-50 via-surface-50/50 to-transparent"></div>
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
                Il mercato dei Meme
                <span class="text-brand">è aperto.</span>
            </h1>
            
            <p class="text-lg sm:text-xl text-text-muted mb-8 max-w-2xl mx-auto">
                Scambia i tuoi CFU contro l'AMM e domina la classifica universitaria.
            </p>
            
            <!-- CTA Button -->
            <div class="mb-12">
                <a href="{{ route('auth.register') }}" class="inline-block w-full sm:w-auto">
                    <x-forms.button variant="primary" size="lg" rounded="full" class="sm:w-auto px-20 py-4 text-base font-bold shadow-lg shadow-brand/20 hover:shadow-xl hover:shadow-brand/30">
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
                <!-- First 2 Memes (Fully Visible) -->
                @foreach($topMemes->take(2) as $index => $meme)
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
                
                <!-- Blurred Memes Section with Lock Overlay -->
                <div class="relative">
                    <!-- Third Meme (Partially visible with blur) -->
                    @if($topMemes->count() >= 3)
                        <div class="relative">
                            <x-meme.card-compact 
                                mode="landing"
                                :rank="3"
                                :name="$topMemes[2]['name']"
                                :image="$topMemes[2]['image']" 
                                :ticker="$topMemes[2]['ticker']"
                                :price="$topMemes[2]['price']"
                                :change="$topMemes[2]['change']"
                                :volume="$topMemes[2]['volume24h']"
                            />
                        </div>
                    @endif
                    
                    <!-- Fourth Meme (Hidden/Very blurred) -->
                    @if($topMemes->count() >= 4)
                        <div class="mt-3 blur-lg opacity-30">
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
                    
                    <!-- Lock Overlay (Centered on bottom half of 3rd and 4th memes) -->
                    <div class="absolute inset-x-0 bottom-0 translate-y-6 h-48 flex flex-col items-center justify-center pointer-events-none">
                        <!-- Gradient background -->
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-surface-50/70 to-surface-50"></div>
                        
                        <!-- Lock Content -->
                        <div class="relative z-10 flex flex-col items-center pointer-events-auto">
                            <!-- Lock Icon in Circle -->
                            <div class="lock-icon-container mb-3">
                                <svg class="w-7 h-7 text-brand-light" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C9.243 2 7 4.243 7 7v3H6c-1.103 0-2 .897-2 2v8c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-8c0-1.103-.897-2-2-2h-1V7c0-2.757-2.243-5-5-5zM9 7c0-1.654 1.346-3 3-3s3 1.346 3 3v3H9V7z"/>
                                </svg>
                            </div>
                            
                            <!-- Lock Text -->
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-bold text-text-main mb-1">Market Data Locked</h3>
                                <p class="text-sm text-text-muted">Join other students trading CFUs.</p>
                            </div>
                            
                            <!-- CTA Button (Pill-shaped) -->
                            <a href="{{ route('auth.register') }}" class="inline-block w-full max-w-md px-4">
                                <x-forms.button variant="outline-neon" size="lg" rounded="full" class="w-full px-24 py-4">
                                    <span>Sblocca Ora</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
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
