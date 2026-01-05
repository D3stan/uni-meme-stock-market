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

<div class="bg-gradient-to-br from-green-900 via-green-800 to-green-900 rounded-3xl p-6 shadow-lg">
    {{-- Label --}}
    <p class="text-gray-300 text-sm font-medium mb-2 uppercase tracking-wide">Valore Totale</p>
    
    {{-- Net Worth Value --}}
    <div class="flex items-center justify-center gap-3 mb-4">
        <h1 class="text-5xl font-black text-white font-mono" id="net-worth-value">
            {{ $formattedNetWorth }}
        </h1>
        <span class="text-3xl font-bold text-green-400">CFU</span>
        
        {{-- Eye Toggle --}}
        <button 
            type="button"
            onclick="toggleNetWorthVisibility()"
            class="ml-2 p-2 hover:bg-green-800/50 rounded-lg transition-colors"
            aria-label="Toggle visibility"
        >
            <span class="material-icons text-white text-2xl" id="visibility-icon">visibility</span>
        </button>
    </div>
    
    {{-- Daily Change Badge --}}
    <div class="flex justify-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $isPositive ? 'bg-green-500/20 text-green-300' : 'bg-red-500/20 text-red-300' }}">
            <span class="material-icons text-sm">
                {{ $isPositive ? 'trending_up' : 'trending_down' }}
            </span>
            <span class="font-bold text-sm">
                {{ $formattedPct }}% (24h)
            </span>
        </div>
    </div>
</div>
