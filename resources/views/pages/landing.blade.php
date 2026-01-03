<x-guest title="AlmaStreet - Il mercato dei meme universitari">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-40 bg-background/80 backdrop-blur-md border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <img src="{{ asset('icon.png') }}" alt="AlmaStreet" class="h-8 w-8">
                    <span class="text-xl font-bold text-white">AlmaStreet</span>
                </div>
                
                <!-- Login Button -->
                <a href="{{ route('auth.login') }}" class="text-sm text-gray-300 hover:text-white transition-colors">
                    Accedi
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="pt-24 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white mb-4 leading-tight">
                Il mercato dei meme<br>
                <span class="text-green-500">universitari</span>
            </h1>
            
            <p class="text-lg sm:text-xl text-gray-400 mb-8 max-w-2xl mx-auto">
                Fai trading sui meme più virali dell'ateneo, scala la classifica e diventa il trader più quotato del campus
            </p>
            
            <!-- CTA Button -->
            <div class="mb-12">
                <a href="{{ route('auth.register') }}" class="inline-block w-full sm:w-auto">
                    <x-forms.button variant="success" size="lg" class="w-full sm:w-auto px-12 py-4 text-base font-bold rounded-xl">
                        Inizia a fare Trading
                    </x-forms.button>
                </a>
                <p class="text-sm text-gray-500 mt-3">
                    Registrati con la tua email istituzionale • 100 CFU gratis
                </p>
            </div>
        </div>
    </div>

    <!-- Market Teaser Section (Paywall Effect) -->
    <div class="px-4 sm:px-6 lg:px-8 pb-16">
        <div class="max-w-4xl mx-auto">
            <h2 class="text-xl font-semibold text-white mb-4">Top Gainers 🚀</h2>
            
            <div class="space-y-4 relative">
                <!-- Meme Preview Cards (3 visible) -->
                @for($i = 1; $i <= 3; $i++)
                <div class="bg-gray-900 border border-gray-800 rounded-lg p-4 flex items-center gap-4">
                    <div class="w-16 h-16 bg-gray-800 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-bold text-white">MEME #{{ $i }}</span>
                            <span class="text-xs px-2 py-0.5 bg-green-900/30 text-green-400 rounded-full font-medium">
                                +{{ 12 + $i * 5 }}.{{ rand(0, 9) }}%
                            </span>
                        </div>
                        <p class="text-sm text-gray-400">{{ rand(50, 200) }} traders attivi</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-white font-mono">{{ number_format(rand(100, 500) / 10, 2) }} CFU</p>
                        <p class="text-xs text-gray-500">Prezzo corrente</p>
                    </div>
                </div>
                @endfor
                
                <!-- Blurred 4th card + Conversion Block -->
                <div class="relative">
                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-4 flex items-center gap-4 blur-sm opacity-50">
                        <div class="w-16 h-16 bg-gray-800 rounded-lg"></div>
                        <div class="flex-1">
                            <div class="h-4 bg-gray-800 rounded w-24 mb-2"></div>
                            <div class="h-3 bg-gray-800 rounded w-32"></div>
                        </div>
                    </div>
                    
                    <!-- Conversion Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="text-center bg-background/95 backdrop-blur-sm px-8 py-6 rounded-xl border border-gray-700">
                            <p class="text-white font-medium mb-3">Sblocca il mercato completo</p>
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
    <footer class="mt-auto border-t border-gray-800 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                © 2026 AlmaStreet • Il trading è virtuale e a scopo ludico
            </p>
        </div>
    </footer>
</x-guest>
