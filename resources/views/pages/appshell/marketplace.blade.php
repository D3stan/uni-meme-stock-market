<x-app :active="'market'" :balance="$balance">
    <x-ui.modal id="onboarding-bonus" :show="session('show_onboarding_modal', false)">
        <div class="p-8 text-center">
            <div class="mb-4">🎉</div>
            <h2 class="text-3xl font-bold mb-2 text-text-main">Benvenuto su AlmaStreet!</h2>
            <p class="text-5xl font-black text-brand mb-2">+100 CFU</p>
            <p class="text-text-muted mb-6">Usa questi CFU per iniziare a fare trading!</p>
            <x-forms.button onclick="hideModal('onboarding-bonus')" variant="primary">
                Vai al Mercato
            </x-forms.button>
        </div>
    </x-ui.modal>
    
    {{-- Ticker Tape - Full Width --}}
    <div class="sticky top-[44px] lg:top-18 z-30">
        <x-navigation.ticker :memes="$tickerMemes" />
    </div>

    {{-- Filtri Chips --}}
    <div class="sticky top-[84px] lg:top-[112px] z-20">
        <div class="overflow-x-auto hide-scrollbar px-4 py-3 bg-surface-50 border-b border-surface-200 rounded-b-xl">
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
    </div>

    <div class="max-w-7xl mx-auto mt-2 px-2" data-page="marketplace">

        {{-- Meme Feed Skeleton (Visible by default for JS handling) --}}
        <div id="meme-feed-skeleton" class="py-6 grid grid-cols-1 gap-4">
            <x-meme.skeleton />
            <x-meme.skeleton />
            <x-meme.skeleton />
            <x-meme.skeleton />
            <x-meme.skeleton />
            <x-meme.skeleton />
        </div>

        {{-- Meme Feed (Hidden by default, shown by JS after images load) --}}
        <div id="meme-feed" class="hidden py-6 flex flex-col items-center gap-4">
            @forelse($memes as $meme)
                <x-meme.card 
                    :name="$meme['name']"
                    :alt="$meme['text_alt']"
                    :image="$meme['image']" 
                    :ticker="$meme['ticker']"
                    :price="$meme['price']"
                    :change="$meme['change']"
                    :creatorName="$meme['creatorName']"
                    :creatorAvatar="$meme['creatorAvatar']"
                    :status="$meme['status']"
                    :tradeUrl="route('trade', ['meme' => $meme['id']])"
                    class="w-full lg:w-1/3"
                />
            @empty
                <x-ui.empty-state 
                    icon="search_off"
                    message="Nessun meme trovato con questo filtro"
                />
            @endforelse
        </div>

        {{-- Infinite scroll loading placeholder (gestito da JS) --}}
        <div id="infinite-scroll-loader" class="hidden py-6 text-center text-text-muted">
            Caricamento altri meme...
        </div>

    </div>
</x-app>
