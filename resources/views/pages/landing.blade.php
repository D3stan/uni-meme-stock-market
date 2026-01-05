<x-guest title="Il mercato dei meme universitari">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-40 bg-surface-50/80 backdrop-blur-md border-b border-surface-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <img src="{{ asset('icon.png') }}" alt="AlmaStreet" class="h-8 w-8">
                    <span class="text-xl font-bold text-text-main">AlmaStreet</span>
                </div>
                
                <!-- Login Button -->
                <a href="{{ route('auth.login') }}" class="text-sm text-text-muted hover:text-text-main transition-colors">
                    Accedi
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-text-main mb-4 leading-tight">
                Il mercato dei meme<br>
                <span class="text-brand">universitari</span>
            </h1>
            
            <p class="text-lg sm:text-xl text-text-muted mb-8 max-w-2xl mx-auto">
                Fai trading sui meme più virali dell'ateneo, scala la classifica e diventa il trader più quotato del campus
            </p>
            
            <!-- CTA Button -->
            <div class="mb-12">
                <a href="{{ route('auth.register') }}" class="inline-block w-full sm:w-auto">
                    <x-forms.button variant="primary" size="lg" class="w-full sm:w-auto px-12 py-4 text-base font-bold rounded-xl">
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
            <h2 class="text-xl font-semibold text-text-main mb-4">Top Gainers 🚀</h2>
            
            <div class="space-y-4 relative">
                <!-- Meme Preview Cards (3 visible) -->
                @for($i = 1; $i <= 3; $i++)
                <x-meme.card-compact 
                    name="Un segreto è un segreto"
                    image="storage/test/meme.jpeg" 
                    ticker="SCRT"
                    :price="127.30"
                    :change="-3.2"
                />
                @endfor
                
                <!-- Blurred 4th card + Conversion Block -->
                <div class="relative">
                    <div class="bg-surface-100 border border-surface-200 rounded-lg flex items-center gap-4 blur-sm opacity-50">
                        <x-meme.card-compact 
                            name="Un segreto è un segreto"
                            image="storage/test/meme.jpeg" 
                            ticker="SCRT"
                            :price="127.30"
                            :change="-3.2"
                        />
                    </div>
                    
                    <!-- Conversion Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center bg-surface-50/95 backdrop-blur-sm px-8 py-6 rounded-xl border border-surface-200">
                            <p class="text-text-main font-medium mb-3">Sblocca il mercato completo</p>
                            <a href="{{ route('auth.register') }}">
                                <x-forms.button variant="outline" size="sm">
                                    Registrati ora
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
