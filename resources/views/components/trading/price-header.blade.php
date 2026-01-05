{{-- Price Header Component - Shows current price and 24h change --}}
@props(['price', 'priceChange', 'ticker'])

<div class="text-center py-6 px-4">
    {{-- Current Price - Large monospace font --}}
    <div class="text-5xl font-black font-mono tracking-tight mb-3
        {{ $priceChange['percentage'] >= 0 ? 'text-brand' : 'text-brand-danger' }}">
        {{ number_format($price, 2) }} CFU
    </div>

    {{-- 24h Change Badge --}}
    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border
        {{ $priceChange['percentage'] >= 0 
            ? 'bg-brand/20 border-brand/50 text-brand' 
            : 'bg-brand-danger/20 border-brand-danger/50 text-brand-danger' }}">
        <span class="material-icons text-sm">
            {{ $priceChange['percentage'] >= 0 ? 'arrow_upward' : 'arrow_downward' }}
        </span>
        <span class="font-semibold">
            {{ $priceChange['percentage'] >= 0 ? '+' : '' }}{{ number_format($priceChange['percentage'], 1) }}%
        </span>
    </div>
</div>
