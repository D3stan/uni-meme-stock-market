@props([
    'registrationDate' => null,
    'totalTrades' => 0,
    'badgeCount' => 0,
    'memeCount' => 0,
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
        
        {{-- Badge Guadagnati --}}
        <div class="bg-surface-100 rounded-xl p-4 border border-surface-200">
            <p class="text-text-muted text-xs mb-1">Badge Guadagnati</p>
            <p class="text-2xl font-bold text-text-main">{{ number_format($badgeCount) }}</p>
            <p class="text-xs text-text-muted mt-1">Traguardi</p>
        </div>
        
        {{-- Meme Creati --}}
        <div class="bg-surface-100 rounded-xl p-4 border border-surface-200">
            <p class="text-text-muted text-xs mb-1">Meme Creati</p>
            <p class="text-2xl font-bold text-text-main">{{ number_format($memeCount) }}</p>
            <p class="text-xs text-text-muted mt-1">Proposte</p>
        </div>
    </div>
</div>
