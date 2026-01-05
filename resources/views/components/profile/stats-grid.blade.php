@props([
    'registrationDate' => null,
    'totalTrades' => 0,
    'bestTrade' => null,
    'globalRank' => null,
])

<div class="px-4 mb-6">
    <h2 class="text-xl font-bold text-white mb-4">Statistiche Rapide</h2>
    
    <div class="grid grid-cols-2 gap-4">
        {{-- Iscritto da --}}
        <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
            <p class="text-gray-400 text-xs mb-1">Iscritto da</p>
            <p class="text-2xl font-bold text-white">{{ $registrationDate }}</p>
        </div>
        
        {{-- Trade totali --}}
        <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
            <p class="text-gray-400 text-xs mb-1">Trade totali</p>
            <p class="text-2xl font-bold text-white">{{ number_format($totalTrades) }}</p>
        </div>
        
        {{-- Miglior Trade --}}
        <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
            <p class="text-gray-400 text-xs mb-1">Miglior Trade</p>
            @if($bestTrade)
                <p class="text-2xl font-bold text-green-400">{{ $bestTrade['percentage'] }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $bestTrade['ticker'] }}</p>
            @else
                <p class="text-2xl font-bold text-gray-600">N/A</p>
            @endif
        </div>
        
        {{-- Posizione Globale --}}
        <div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
            <p class="text-gray-400 text-xs mb-1">Posizione</p>
            @if($globalRank)
                <p class="text-2xl font-bold text-white">#{{ $globalRank }}</p>
                <p class="text-xs text-gray-500 mt-1">Global</p>
            @else
                <p class="text-2xl font-bold text-gray-600">N/R</p>
            @endif
        </div>
    </div>
</div>
