@props([
    'netWorth' => 0,
    'dailyChange' => 0,
    'dailyChangePct' => 0,
])

@php
    $isPositive = $dailyChange > 0;
    $formattedNetWorth = number_format($netWorth, 2);
    $formattedChange = ($isPositive ? '+' : '') . number_format($dailyChange, 2);
    $formattedPct = ($isPositive ? '+' : '') . number_format($dailyChangePct, 1);
@endphp

<div class="bg-brand-dark rounded-3xl p-6 shadow-lg">
    {{-- Label --}}
    <p class="text-text-main/70 text-sm font-medium mb-2 uppercase tracking-wide text-center">Valore Totale</p>
    
    {{-- Net Worth Value --}}
    <div class="flex items-center justify-center gap-3 mb-4">
        <h1 class="text-5xl font-black text-text-main font-mono" id="net-worth-value">
            {{ $formattedNetWorth }}
        </h1>
        <span class="text-3xl font-bold text-brand-light">CFU</span>
        
        {{-- Eye Toggle --}}
        <button 
            type="button"
            onclick="toggleNetWorthVisibility()"
            class="ml-2 p-2 hover:bg-white/10 rounded-lg transition-colors"
            aria-label="Toggle visibility"
        >
            <span class="material-icons text-text-main text-2xl" id="visibility-icon">visibility</span>
        </button>
    </div>
    
    {{-- Daily Change Badge --}}
    <div class="flex justify-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $isPositive ? 'badge-positive' : 'badge-negative' }}">
            <span class="material-icons text-sm">
                {{ $isPositive ? 'trending_up' : 'trending_down' }}
            </span>
            <span class="font-bold text-sm">
                {{ $formattedPct }}% (24h)
            </span>
        </div>
    </div>
</div>
