@props([
    'meme' => null,
    'quantity' => 0,
    'currentValue' => 0,
    'avgBuyPrice' => 0,
    'pnlAmount' => 0,
    'pnlPct' => 0,
    'tradeUrl' => '#',
])

@php
    $isProfit = $pnlAmount > 0;
    $formattedValue = number_format($currentValue, 0);
    $formattedPnl = ($isProfit ? '+' : '') . number_format($pnlAmount, 0);
    $formattedPnlPct = ($isProfit ? '+' : '') . number_format($pnlPct, 1);
@endphp

<a href="{{ $tradeUrl }}" class="block group">
    <div class="flex items-center gap-4 bg-gray-800 rounded-2xl p-4 border-2 border-gray-700 hover:border-green-500 transition-all hover:shadow-lg">
        {{-- Meme Image --}}
        <div class="flex-shrink-0">
            <img 
                src="{{ asset($meme->image_path ?? 'images/default-meme.png') }}" 
                alt="{{ $meme->title ?? 'Meme' }}"
                class="w-16 h-16 rounded-xl object-cover"
            >
        </div>
        
        {{-- Meme Info --}}
        <div class="flex-1 min-w-0">
            <h3 class="text-base font-bold text-white truncate">${{ $meme->ticker ?? 'XXX' }}</h3>
            <p class="text-xs text-gray-400 uppercase tracking-wide">{{ $meme->category->name ?? 'MEME' }}</p>
        </div>
        
        {{-- Quantity & Value --}}
        <div class="text-center">
            <p class="text-sm font-bold text-white">{{ number_format($quantity) }} pz</p>
            <p class="text-xs text-gray-400 font-mono">≈{{ $formattedValue }} CFU</p>
        </div>
        
        {{-- P&L --}}
        <div class="text-right flex-shrink-0">
            <p class="text-base font-bold {{ $isProfit ? 'text-green-400' : 'text-red-400' }}">
                {{ $formattedPnlPct }}%
            </p>
            <p class="text-xs font-mono {{ $isProfit ? 'text-green-400' : 'text-red-400' }}">
                {{ $formattedPnl }} CFU
            </p>
        </div>
    </div>
</a>
