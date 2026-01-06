{{-- Trade Station Page - Custom layout extending base (no nav bars) --}}
<x-base :title="$meme->ticker">
    <div class="min-h-screen bg-surface-50 pb-24">
        {{-- Top Navigation Bar --}}
        <div class="sticky top-0 z-40 bg-surface-50/95 backdrop-blur-sm border-b border-surface-200">
            <div class="flex items-center justify-between px-4 py-3">
                {{-- Ticker --}}
                <h1 class="text-xl font-bold">{{ $meme->ticker }}</h1>

                {{-- Back Button --}}
                <a href="{{ route('market') }}" class="p-2 hover:bg-surface-200 rounded-lg transition-colors">
                    <span class="material-icons text-2xl">arrow_back</span>
                </a>
            </div>
        </div>

        {{-- Price Header --}}
        <x-trading.price-header 
            :price="$meme->current_price" 
            :priceChange="$priceChange24h" 
            :ticker="$meme->ticker" />

        {{-- Chart/Meme Toggle + Display --}}
        <x-trading.chart-toggle :meme="$meme" />

        {{-- Stats Section (visible only in chart view) --}}
        <x-trading.stats-section :meme="$meme" :risk="$risk"/>

        {{-- Action Bar (Sticky Bottom) --}}
        <x-trading.action-bar :meme="$meme" />

        {{-- Order Modal --}}
        <x-trading.order-modal :meme="$meme" :userHoldings="$userHoldings" />

        {{-- Slippage Alert Modal --}}
        <x-trading.slippage-modal />
    </div>

    {{-- Toast Notifications --}}
    <x-ui.toast />

    @push('page-scripts')
        {{-- Pass server data to JavaScript --}}
        <script>
            window.TRADING_DATA = {
                memeId: {{ $meme->id }},
                ticker: '{{ $meme->ticker }}',
                currentPrice: {{ $meme->current_price }},
                basePrice: {{ $meme->base_price }},
                slope: {{ $meme->slope }},
                circulatingSupply: {{ $meme->circulating_supply }},
                userBalance: {{ auth()->user()->cfu_balance }},
                userHoldings: {{ $userHoldings ? $userHoldings->quantity : 0 }},
                csrfToken: '{{ csrf_token() }}',
                routes: {
                    preview: '{{ route('api.trade.preview') }}',
                    execute: '{{ route('api.trade.execute') }}',
                    priceHistory: '{{ route('api.trade.price-history', ['meme' => $meme->id, 'period' => ':period']) }}',
                    marketData: '{{ route('api.trade.market-data', ['meme' => $meme->id]) }}',
                    holdings: '{{ route('api.trade.holdings', ['meme' => $meme->id]) }}'
                }
            };
        </script>
        
        {{-- Load trading page JavaScript --}}
        @vite(['resources/js/pages/trading.js'])
    @endpush
</x-base>
