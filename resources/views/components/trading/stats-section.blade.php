{{-- Key Statistics Section --}}
@props(['meme'])

<div id="stats-section" class="px-4 mb-6">
    <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wide mb-3">
        Statistiche Chiave
    </h3>
    
    <div class="grid grid-cols-2 gap-3">
        {{-- Market Cap --}}
        <div class="bg-gray-800/50 rounded-lg p-4">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Market Cap</div>
            <div class="text-xl font-bold font-mono">
                {{ number_format($meme->current_price * $meme->circulating_supply / 1000, 1) }}k CFU
            </div>
        </div>

        {{-- 24h Volume --}}
        <div class="bg-gray-800/50 rounded-lg p-4">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Volume 24H</div>
            <div class="text-xl font-bold font-mono">
                {{ number_format(($meme->transactions()->where('executed_at', '>=', now()->subDay())->sum('total_amount') ?? 0) / 1000, 0) }}k CFU
            </div>
        </div>

        {{-- Supply --}}
        <div class="bg-gray-800/50 rounded-lg p-4">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Supply</div>
            <div class="text-xl font-bold font-mono">
                {{ number_format($meme->circulating_supply) }}
            </div>
        </div>

        {{-- Risk Level --}}
        <div class="bg-gray-800/50 rounded-lg p-4">
            <div class="text-xs text-gray-400 uppercase tracking-wide mb-1">Rischio</div>
            <div class="text-xl font-bold text-red-400 flex items-center gap-1">
                <span>Alto</span>
                <span class="material-icons text-lg">warning</span>
            </div>
        </div>
    </div>
</div>
