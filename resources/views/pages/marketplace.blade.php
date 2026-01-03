<x-app :active="'market'" :balance="$balance">
    <div class="min-h-screen pb-20 lg:pb-8 lg:pt-20">
        <div class="max-w-7xl mx-auto">
            
            {{-- Filtri Chips --}}
            <div class="overflow-x-auto hide-scrollbar px-4 py-3 bg-input-background/50 border-b border-gray-800/50 rounded-xl">
                <div class="flex gap-2 min-w-max">
                    <a href="{{ route('market', ['filter' => 'all']) }}" class="whitespace-nowrap">
                        <x-ui.chip 
                            :active="$filter === 'all'"
                            variant="{{ $filter === 'all' ? 'success' : 'white' }}"
                        >
                            Tutti
                        </x-ui.chip>
                    </a>
                    
                    <a href="{{ route('market', ['filter' => 'top_gainer']) }}" class="whitespace-nowrap">
                        <x-ui.chip 
                            icon="🔥" 
                            :active="$filter === 'top_gainer'"
                            variant="{{ $filter === 'top_gainer' ? 'success' : 'outline' }}"
                        >
                            Top Gainer
                        </x-ui.chip>
                    </a>
                    
                    <a href="{{ route('market', ['filter' => 'new_listing']) }}" class="whitespace-nowrap">
                        <x-ui.chip 
                            icon="🆕" 
                            :active="$filter === 'new_listing'"
                            variant="{{ $filter === 'new_listing' ? 'success' : 'outline' }}"
                        >
                            New Listing
                        </x-ui.chip>
                    </a>
                    
                    <a href="{{ route('market', ['filter' => 'high_risk']) }}" class="whitespace-nowrap">
                        <x-ui.chip 
                            icon="⚠️" 
                            :active="$filter === 'high_risk'"
                            variant="{{ $filter === 'high_risk' ? 'success' : 'outline' }}"
                        >
                            High Risk
                        </x-ui.chip>
                    </a>
                </div>
            </div>

            {{-- Meme Feed --}}
            <div class="px-4 py-6 grid grid-cols-1 lg:grid-cols-2 gap-4">
                @forelse($memes as $meme)
                    <x-meme.card 
                        :name="$meme['name']"
                        :image="$meme['image']" 
                        :ticker="$meme['ticker']"
                        :price="$meme['price']"
                        :change="$meme['change']"
                        :creatorName="$meme['creatorName']"
                        :status="$meme['status']"
                        :tradeUrl="route('trade', ['id' => $meme['id']])"
                    />
                @empty
                    <x-ui.empty-state 
                        icon="search_off"
                        message="Nessun meme trovato con questo filtro"
                    />
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($memes->hasPages())
                <div class="px-4 pb-6">
                    {{ $memes->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app>
