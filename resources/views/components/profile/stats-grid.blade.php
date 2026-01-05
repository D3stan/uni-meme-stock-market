@props([
    'registrationDate' => null,
    'totalTrades' => 0,
    'bestTrade' => null,
    'globalRank' => null,
])

<div class="px-4 mb-6">
    <h2 class="text-xl font-bold text-text-main mb-4">Statistiche Rapide</h2>
    
    <div class="grid grid-cols-2 gap-4">
        {{-- Iscritto da --}}
        <div class="bg-surface-100 rounded-xl p-4 border border-surface-200">
            <p class="text-text-muted text-xs mb-1">Iscritto da</p>
            <p class="text-2xl font-bold text-text-main">{{ $registrationDate }}</p>
        </div>
        
        {{-- Trade totali --}}
        <div class="bg-surface-100 rounded-xl p-4 border border-surface-200">
            <p class="text-text-muted text-xs mb-1">Trade totali</p>
            <p class="text-2xl font-bold text-text-main">{{ number_format($totalTrades) }}</p>
        </div>
        
        {{-- Miglior Trade --}}
        <div class="bg-surface-100 rounded-xl p-4 border border-surface-200">
            <p class="text-text-muted text-xs mb-1">Miglior Trade</p>
            @if($bestTrade)
                <p class="text-2xl font-bold text-brand">{{ $bestTrade['percentage'] }}</p>
                <p class="text-xs text-text-muted mt-1">{{ $bestTrade['ticker'] }}</p>
            @else
                <p class="text-2xl font-bold text-text-muted">N/A</p>
            @endif
        </div>
        
        {{-- Posizione Globale --}}
        <div class="bg-surface-100 rounded-xl p-4 border border-surface-200">
            <p class="text-text-muted text-xs mb-1">Posizione</p>
            @if($globalRank)
                <p class="text-2xl font-bold text-text-main">#{{ $globalRank }}</p>
                <p class="text-xs text-text-muted mt-1">Global</p>
            @else
                <p class="text-2xl font-bold text-text-muted">N/R</p>
            @endif
        </div>
    </div>
</div>
