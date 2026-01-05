<x-app :active="'portfolio'" :balance="$balance">
    <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
        
        {{-- Hero Section: Net Worth --}}
        <x-portfolio.net-worth-hero 
            :netWorth="$netWorth"
            :dailyChange="$dailyChange"
            :dailyChangePct="$dailyChangePct"
        />
        
        {{-- Asset Allocation Chart --}}
        <x-portfolio.allocation-chart 
            :invested="$totalInvested"
            :liquid="$liquidBalance"
        />
        
        {{-- Holdings List --}}
        <div class="space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="text-xl font-bold text-white">I Tuoi Meme</h2>
                @if($positions->count() > 3)
                    <a href="#" class="text-sm font-semibold text-green-500 uppercase tracking-wide hover:text-green-400">
                        Vedi Tutti
                    </a>
                @endif
            </div>
            
            @forelse($positions->take(4) as $position)
                <x-portfolio.position-row 
                    :meme="$position['meme']"
                    :quantity="$position['quantity']"
                    :currentValue="$position['current_value']"
                    :avgBuyPrice="$position['avg_buy_price']"
                    :pnlAmount="$position['pnl_amount']"
                    :pnlPct="$position['pnl_pct']"
                    :tradeUrl="route('trade', ['meme' => $position['meme']->id])"
                />
            @empty
                <x-ui.empty-state 
                    icon="account_balance_wallet"
                    message="Nessuna posizione aperta"
                >
                    <a href="{{ route('market') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl transition-colors">
                        <span class="material-icons text-lg">trending_up</span>
                        Esplora il Mercato
                    </a>
                </x-ui.empty-state>
            @endforelse
        </div>
        
    </div>
</x-app>