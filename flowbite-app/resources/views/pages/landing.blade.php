@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-gray-950/80 backdrop-blur-md border-b border-gray-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-14 sm:h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5.5 16a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 16h-8z"/>
                        </svg>
                    </div>
                    <span class="text-lg sm:text-xl font-bold text-white">AlmaStreet</span>
                </div>
                
                <!-- Login Button -->
                <a href="{{ route('login.page') }}" 
                   class="text-sm font-medium text-gray-300 hover:text-white transition-colors">
                    Login
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content - Full viewport height with flex distribution -->
    <main class="min-h-screen pt-14 sm:pt-16 flex flex-col lg:block">
        <!-- Hero Section with Chart Background -->
        <section class="relative overflow-hidden flex-shrink-0 lg:flex-shrink">
            <!-- Chart Background -->
            <div class="absolute inset-0 opacity-40">
                <svg class="w-full h-full" viewBox="0 0 800 400" preserveAspectRatio="xMidYMid slice">
                    <defs>
                        <linearGradient id="chartGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#10b981;stop-opacity:0.3" />
                            <stop offset="100%" style="stop-color:#10b981;stop-opacity:0" />
                        </linearGradient>
                    </defs>
                    <!-- Grid lines -->
                    <g stroke="#374151" stroke-width="0.5" opacity="0.3">
                        @for($i = 0; $i < 10; $i++)
                            <line x1="0" y1="{{ $i * 40 }}" x2="800" y2="{{ $i * 40 }}" />
                        @endfor
                        @for($i = 0; $i < 20; $i++)
                            <line x1="{{ $i * 40 }}" y1="0" x2="{{ $i * 40 }}" y2="400" />
                        @endfor
                    </g>
                    <!-- Candlesticks -->
                    <g>
                        @php
                            $candles = [
                                ['x' => 50, 'open' => 280, 'close' => 250, 'high' => 240, 'low' => 290],
                                ['x' => 80, 'open' => 250, 'close' => 220, 'high' => 210, 'low' => 260],
                                ['x' => 110, 'open' => 220, 'close' => 260, 'high' => 200, 'low' => 270],
                                ['x' => 140, 'open' => 260, 'close' => 230, 'high' => 220, 'low' => 280],
                                ['x' => 170, 'open' => 230, 'close' => 200, 'high' => 190, 'low' => 250],
                                ['x' => 200, 'open' => 200, 'close' => 180, 'high' => 170, 'low' => 220],
                                ['x' => 230, 'open' => 180, 'close' => 210, 'high' => 160, 'low' => 220],
                                ['x' => 260, 'open' => 210, 'close' => 190, 'high' => 180, 'low' => 230],
                                ['x' => 290, 'open' => 190, 'close' => 220, 'high' => 170, 'low' => 230],
                                ['x' => 320, 'open' => 220, 'close' => 200, 'high' => 190, 'low' => 240],
                                ['x' => 350, 'open' => 200, 'close' => 170, 'high' => 160, 'low' => 220],
                                ['x' => 380, 'open' => 170, 'close' => 150, 'high' => 140, 'low' => 190],
                                ['x' => 410, 'open' => 150, 'close' => 180, 'high' => 130, 'low' => 190],
                                ['x' => 440, 'open' => 180, 'close' => 160, 'high' => 150, 'low' => 200],
                                ['x' => 470, 'open' => 160, 'close' => 140, 'high' => 130, 'low' => 180],
                                ['x' => 500, 'open' => 140, 'close' => 170, 'high' => 120, 'low' => 180],
                                ['x' => 530, 'open' => 170, 'close' => 150, 'high' => 140, 'low' => 190],
                                ['x' => 560, 'open' => 150, 'close' => 130, 'high' => 120, 'low' => 170],
                                ['x' => 590, 'open' => 130, 'close' => 160, 'high' => 110, 'low' => 170],
                                ['x' => 620, 'open' => 160, 'close' => 140, 'high' => 130, 'low' => 180],
                                ['x' => 650, 'open' => 140, 'close' => 120, 'high' => 110, 'low' => 160],
                                ['x' => 680, 'open' => 120, 'close' => 150, 'high' => 100, 'low' => 160],
                                ['x' => 710, 'open' => 150, 'close' => 130, 'high' => 120, 'low' => 170],
                                ['x' => 740, 'open' => 130, 'close' => 110, 'high' => 100, 'low' => 150],
                            ];
                        @endphp
                        @foreach($candles as $candle)
                            @php
                                $isGreen = $candle['close'] < $candle['open'];
                                $color = $isGreen ? '#10b981' : '#ef4444';
                                $top = min($candle['open'], $candle['close']);
                                $height = abs($candle['open'] - $candle['close']);
                            @endphp
                            <!-- Wick -->
                            <line x1="{{ $candle['x'] }}" y1="{{ $candle['high'] }}" 
                                  x2="{{ $candle['x'] }}" y2="{{ $candle['low'] }}" 
                                  stroke="{{ $color }}" stroke-width="1" />
                            <!-- Body -->
                            <rect x="{{ $candle['x'] - 8 }}" y="{{ $top }}" 
                                  width="16" height="{{ max($height, 2) }}" 
                                  fill="{{ $color }}" />
                        @endforeach
                    </g>
                </svg>
            </div>
            
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-b from-gray-950/60 via-gray-950/80 to-gray-950"></div>
            
            <!-- Hero Content -->
            <div class="relative px-4 sm:px-6 lg:px-8 py-8 sm:py-12 lg:py-20">
                <div class="max-w-2xl mx-auto text-center">
                    <!-- Market Status Badge -->
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 mb-4">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                        <span class="text-xs sm:text-sm font-medium text-emerald-400 uppercase tracking-wide">Market Open</span>
                    </div>
                    
                    <!-- Main Heading -->
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight mb-3">
                        Il mercato dei Meme è aperto.
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-base sm:text-lg text-gray-400 mb-6 max-w-md mx-auto">
                        Scambia i tuoi CFU contro l'AMM e domina la classifica universitaria.
                    </p>
                    
                    <!-- Primary CTA -->
                    <a href="{{ route('register.page') }}" 
                       class="inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-gray-900 font-semibold text-base sm:text-lg rounded-xl transition-all duration-200 transform hover:scale-[1.02] shadow-lg shadow-emerald-500/25">
                        Inizia a fare Trading
                    </a>
                </div>
            </div>
        </section>

        <!-- Top Movers Section - Takes remaining space -->
        <section class="px-4 sm:px-6 lg:px-8 py-4 pb-6 flex-1 flex flex-col lg:flex-none lg:py-8">
            <div class="max-w-lg mx-auto flex-1 flex flex-col lg:block">
                <!-- Section Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <h2 class="text-xl sm:text-2xl font-bold text-white">Top Movers</h2>
                        <span class="text-xl">🔥</span>
                    </div>
                    <div class="flex items-center space-x-4 text-xs text-gray-500 font-mono">
                        <span>Vol.</span>
                        <span>24h</span>
                    </div>
                </div>

                <!-- Meme List -->
                <div class="space-y-2">
                    @forelse($topMemes as $index => $meme)
                        <div class="relative {{ $index >= 3 ? 'overflow-hidden' : '' }}">
                            <!-- Meme Card -->
                            <div class="flex items-center justify-between p-4 bg-gray-900/50 rounded-xl border border-gray-800/50 {{ $index >= 3 ? 'opacity-50 blur-[2px]' : '' }}">
                                <!-- Left: Rank + Image + Info -->
                                <div class="flex items-center space-x-4">
                                    <!-- Rank -->
                                    <span class="text-sm text-gray-500 font-medium w-4">{{ $meme['rank'] }}</span>
                                    
                                    <!-- Meme Image -->
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center overflow-hidden ring-2 ring-gray-700">
                                        @if(str_contains(strtolower($meme['ticker']), 'stonk'))
                                            <span class="text-2xl">📈</span>
                                        @elseif(str_contains(strtolower($meme['ticker']), 'hodl'))
                                            <span class="text-2xl">💎</span>
                                        @elseif(str_contains(strtolower($meme['ticker']), 'esame'))
                                            <span class="text-2xl">📚</span>
                                        @elseif(str_contains(strtolower($meme['ticker']), 'press'))
                                            <span class="text-2xl">🎮</span>
                                        @else
                                            <span class="text-2xl">🐸</span>
                                        @endif
                                    </div>
                                    
                                    <!-- Ticker & Volume -->
                                    <div>
                                        <p class="font-bold text-white">{{ $meme['ticker'] }}</p>
                                        <p class="text-sm text-gray-500">Vol: {{ $meme['volume'] }}</p>
                                    </div>
                                </div>
                                
                                <!-- Right: Price Change -->
                                <div class="flex items-center space-x-1 px-3 py-1.5 rounded-lg {{ $meme['change'] >= 0 ? 'bg-emerald-500/10' : 'bg-red-500/10' }}">
                                    <svg class="w-4 h-4 {{ $meme['change'] >= 0 ? 'text-emerald-500' : 'text-red-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($meme['change'] >= 0)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                        @endif
                                    </svg>
                                    <span class="font-mono font-semibold {{ $meme['change'] >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                                        {{ $meme['change'] >= 0 ? '+' : '' }}{{ number_format($meme['change'], 1) }}%
                                    </span>
                                </div>
                            </div>

                            @if($index === 3)
                                <!-- Lock Overlay for 4th item -->
                                <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-950/60 rounded-xl">
                                    <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center mb-2">
                                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <p class="text-white font-semibold text-sm">Market Data Locked</p>
                                </div>
                            @endif
                        </div>
                    @empty
                        <!-- Empty State - Show placeholder memes -->
                        @php
                            $placeholderMemes = [
                                ['rank' => 1, 'ticker' => '$PEPE', 'volume' => '12.5k CFU', 'change' => 15.4],
                                ['rank' => 2, 'ticker' => '$DOGE', 'volume' => '10.2k CFU', 'change' => 8.2],
                                ['rank' => 3, 'ticker' => '$SHIB', 'volume' => '8.9k CFU', 'change' => 4.1],
                                ['rank' => 4, 'ticker' => '$WIF', 'volume' => '7.3k CFU', 'change' => 2.3],
                            ];
                        @endphp
                        @foreach($placeholderMemes as $index => $meme)
                            <div class="relative {{ $index >= 3 ? 'overflow-hidden' : '' }}">
                                <div class="flex items-center justify-between p-4 bg-gray-900/50 rounded-xl border border-gray-800/50 {{ $index >= 3 ? 'opacity-50 blur-[2px]' : '' }}">
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-500 font-medium w-4">{{ $meme['rank'] }}</span>
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center ring-2 ring-gray-700">
                                            <span class="text-2xl">{{ ['🐸', '🐕', '🐶', '🎩'][$index] }}</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white">{{ $meme['ticker'] }}</p>
                                            <p class="text-sm text-gray-500">Vol: {{ $meme['volume'] }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-1 px-3 py-1.5 rounded-lg bg-emerald-500/10">
                                        <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                        </svg>
                                        <span class="font-mono font-semibold text-emerald-500">+{{ $meme['change'] }}%</span>
                                    </div>
                                </div>
                                @if($index === 3)
                                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-gray-950/60 rounded-xl">
                                        <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center mb-2">
                                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <p class="text-white font-semibold text-sm">Market Data Locked</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endforelse
                </div>

                <!-- Secondary CTA - Pushed to bottom on mobile -->
                <div class="mt-auto pt-4 lg:mt-6 lg:pt-0">
                    <a href="{{ route('register.page') }}" 
                       class="flex items-center justify-center w-full px-6 py-4 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 font-semibold rounded-xl transition-all duration-200 group">
                        <span>Registrati per sbloccare</span>
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section (Optional - Desktop) -->
        <section class="hidden lg:block px-4 sm:px-6 lg:px-8 py-16 border-t border-gray-800/50">
            <div class="max-w-5xl mx-auto">
                <div class="grid grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">💰</span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">100 CFU Bonus</h3>
                        <p class="text-sm text-gray-400">Ricevi 100 CFU gratis alla registrazione per iniziare subito a fare trading.</p>
                    </div>
                    
                    <!-- Feature 2 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">📊</span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Trading Istantaneo</h3>
                        <p class="text-sm text-gray-400">Prezzi dinamici con AMM bonding curves - compra e vendi istantaneamente.</p>
                    </div>
                    
                    <!-- Feature 3 -->
                    <div class="text-center">
                        <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <span class="text-3xl">🏆</span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Dean's List</h3>
                        <p class="text-sm text-gray-400">Scala la classifica, guadagna badge e diventa una leggenda del trading.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer - Hidden on mobile to keep CTA at bottom -->
    <footer class="hidden lg:block py-6 px-4 border-t border-gray-800/50">
        <div class="max-w-lg mx-auto text-center">
            <p class="text-xs text-gray-500">
                Built for University of Bologna students
            </p>
            <p class="text-xs text-gray-600 mt-1">
                AlmaStreet © {{ date('Y') }}
            </p>
        </div>
    </footer>
</div>
@endsection
