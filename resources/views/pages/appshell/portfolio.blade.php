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
                <h2 class="text-xl font-bold text-text-main">I Tuoi Meme</h2>
            </div>
            
            @forelse($positions as $position)
                <a href="{{ route('trade', ['meme' => $position['meme']->id]) }}" class="block">
                    <x-meme.card-compact
                        mode="portfolio"
                        :name="$position['meme']->title"
                        :ticker="$position['meme']->ticker"
                        :image="asset('storage/data/' . $position['meme']->creator_id . '/' . $position['meme']->image_path)"
                        :quantity="$position['quantity']"
                        :currentValue="$position['current_value']"
                        :change="$position['pnl_pct']"
                    />
                </a>
            @empty
                <x-ui.empty-state 
                    icon="account_balance_wallet"
                    message="Nessuna posizione aperta"
                >
                    <a href="{{ route('market') }}" class="btn-primary inline-flex items-center gap-2">
                        <span class="material-icons text-lg">trending_up</span>
                        Esplora il Mercato
                    </a>
                </x-ui.empty-state>
            @endforelse
        </div>
        
    </div>

    @push('page-scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        @vite(['resources/js/pages/portfolio.js'])
    @endpush
</x-app>