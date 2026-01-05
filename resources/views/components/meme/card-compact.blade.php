@props([
    'image' => null,
    'name' => 'Meme Name',
    'ticker' => 'XXX',
    'price' => 0,
    'change' => 0,
    'sparklineData' => [], // Array di valori per il grafico
    // Portfolio mode props
    'mode' => 'market', // 'market' or 'portfolio'
    'quantity' => 0,
    'currentValue' => 0,
])

@php
    $isPositive = $change > 0;
    $sparklineColor = $isPositive ? '#10b981' : '#ef4444';
    $isPortfolioMode = $mode === 'portfolio';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3 bg-gray-800 rounded-2xl p-3 shadow-md hover:shadow-lg transition-shadow cursor-pointer border-2 border-gray-700']) }}>
    {{-- Immagine Meme --}}
    <img 
        src="{{  asset($image) }}" 
        alt="{{ $name }}"
        class="w-16 h-16 rounded-lg object-cover flex-shrink-0"
    >
    
    {{-- Contenuto Centrale --}}
    <div class="flex-1 min-w-0">
        <h3 class="text-sm font-medium text-white truncate">{{ $name }}</h3>
        <p class="text-xs text-gray-400">${{ $ticker }}</p>
    </div>
    
    @if($isPortfolioMode)
        {{-- Portfolio Mode: Quantity & Value --}}
        <div class="flex-shrink-0 text-center">
            <p class="text-sm font-bold text-white">{{ number_format($quantity) }} pz</p>
            <p class="text-xs text-gray-400 font-mono">≈{{ number_format($currentValue, 0) }} CFU</p>
        </div>
    @else
        {{-- Market Mode: Sparkline Chart --}}
        <div class="flex-shrink-0 w-20 h-6">
            <svg viewBox="0 0 80 24" class="w-full h-full" preserveAspectRatio="none">
                <polyline
                    points="0,18 20,12 40,15 60,8 80,{{ $isPositive ? '6' : '18' }}"
                    fill="none"
                    stroke="{{ $sparklineColor }}"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </svg>
        </div>
    @endif
    
    {{-- Prezzo/Valore e Variazione --}}
    <div class="text-right flex-shrink-0">
        <p class="text-sm font-bold font-mono text-white">
            {{ $isPortfolioMode ? number_format($currentValue, 2) : number_format($price, 2) }} CFU
        </p>
        <x-ui.badge-change :value="$change" size="sm" class="mt-1" />
    </div>
</div>
