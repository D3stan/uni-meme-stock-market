{{-- Price Header Component - Shows current price and 24h change --}}
@props(['price', 'priceChange', 'ticker'])

<div class="text-center py-6 px-4">
    <div class="flex items-center justify-center gap-4">
        {{-- Current Price - Large monospace font --}}
        <div class="text-5xl font-black font-mono tracking-tight leading-none
            {{ $priceChange['percentage'] >= 0 ? 'text-brand' : 'text-brand-danger' }}">
            {{ number_format($price, 2) }} CFU
        </div>
        {{-- 24h Change Badge (aligned next to price) --}}
        <x-ui.badge-change value="{{ $priceChange['percentage'] >= 0 ? '+' : '' }}{{ number_format($priceChange['percentage'], 1) }}" size="lg" class="-translate-y-1"/>
    </div>
</div>
