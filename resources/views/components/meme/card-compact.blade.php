@props([
    'image' => null,
    'name' => 'Meme Name',
    'ticker' => 'XXX',
    'price' => 0,
    'change' => 0,
    'sparklineData' => [], // Array di valori per il grafico
    // Portfolio mode props
    'mode' => 'market', // 'market' or 'portfolio' or 'landing'
    'quantity' => 0,
    'currentValue' => 0,
    // Landing mode props
    'rank' => null,
    'volume' => 0,
])

@php
    $isPositive = $change > 0;
    $sparklineColor = $isPositive ? '#10b981' : '#ef4444';
    $isPortfolioMode = $mode === 'portfolio';
    $isLandingMode = $mode === 'landing';
    
    // Format volume for landing mode
    $formattedVolume = '';
    if ($isLandingMode && $volume > 0) {
        if ($volume >= 1000) {
            $formattedVolume = number_format($volume / 1000, 1) . 'k';
        } else {
            $formattedVolume = number_format($volume, 0);
        }
    }
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3 bg-surface-100 rounded-2xl p-3 shadow-md hover:shadow-lg transition-shadow cursor-pointer border-2 border-surface-200']) }}>
    @if($isLandingMode && $rank)
        {{-- Ranking Number --}}
        <div class="flex-shrink-0 w-8 text-center">
            <span class="text-2xl font-bold text-text-muted">{{ $rank }}</span>
        </div>
    @endif
    
    {{-- Immagine Meme --}}
    <img 
        src="{{  asset($image) }}" 
        alt="{{ $name }}"
        class="{{ $isLandingMode ? 'w-12 h-12 rounded-full' : 'w-16 h-16 rounded-lg' }} object-cover flex-shrink-0"
    >
    
    {{-- Contenuto Centrale --}}
    <div class="flex-1 min-w-0">
        <h3 class="text-sm font-medium text-text-main truncate">{{ $isLandingMode ? '$' . $ticker : $name }}</h3>
        <p class="text-xs text-text-muted">{{ $isLandingMode ? 'Vol: ' . $formattedVolume . ' CFU' : '$' . $ticker }}</p>
    </div>
    
    @if($isPortfolioMode)
        {{-- Portfolio Mode: Quantity & Value --}}
        <div class="flex-shrink-0 text-center">
            <p class="text-sm font-bold text-text-main">{{ number_format($quantity) }} pz</p>
            <p class="text-xs text-text-muted font-mono">≈{{ number_format($currentValue, 0) }} CFU</p>
        </div>
    @elseif(!$isLandingMode)
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
    @if(!$isLandingMode)
        <div class="text-right flex-shrink-0">
            <p class="text-sm font-bold font-mono text-text-main">
                {{ $isPortfolioMode ? number_format($currentValue, 2) : number_format($price, 2) }} CFU
            </p>
            <x-ui.badge-change :value="$change" size="sm" class="mt-1" />
        </div>
    @else
        {{-- Landing Mode: Only Badge --}}
        <div class="flex-shrink-0">
            <x-ui.badge-change :value="$change" size="md" />
        </div>
    @endif
</div>
