<x-app :active="'leaderboard'" :balance="$balance">
    <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
        
        {{-- Hero Section: Title --}}
        <header class="text-center space-y-2 py-4">
            <h1 class="text-4xl font-black text-text-main">Dean's List</h1>
            <p class="text-text-muted text-base">I migliori trader dell'ateneo</p>
        </header>
        
        {{-- Time Period Filters --}}
        <nav aria-label="Filtri temporali" class="flex gap-3 overflow-x-auto hide-scrollbar pb-2 justify-center">
            <a 
                href="{{ route('leaderboard', ['period' => 'all']) }}" 
                class="filter-chip px-6 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all {{ ($period ?? 'all') === 'all' ? 'bg-brand text-surface-50 shadow-lg shadow-brand/20' : 'bg-surface-200 text-text-main hover:bg-surface-200/80' }}"
            >
                Tutti
            </a>
            <a 
                href="{{ route('leaderboard', ['period' => 'week']) }}" 
                class="filter-chip px-6 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all {{ ($period ?? 'all') === 'week' ? 'bg-brand text-surface-50 shadow-lg shadow-brand/20' : 'bg-surface-200 text-text-main hover:bg-surface-200/80' }}"
            >
                Questa Settimana
            </a>
            <a 
                href="{{ route('leaderboard', ['period' => 'month']) }}" 
                class="filter-chip px-6 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all {{ ($period ?? 'all') === 'month' ? 'bg-brand text-surface-50 shadow-lg shadow-brand/20' : 'bg-surface-200 text-text-main hover:bg-surface-200/80' }}"
            >
                Questo Mese
            </a>
        </nav>
        
        {{-- Podium (Top 3) --}}
        @if(isset($topThree) && count($topThree) > 0)
            <x-leaderboard.podium 
                :first="$topThree[0] ?? null"
                :second="$topThree[1] ?? null"
                :third="$topThree[2] ?? null"
            />
        @endif
        
        {{-- Remaining Rankings (4th onwards) --}}
        <div class="space-y-4 pt-4">
            @forelse($rankings ?? [] as $user)
                @if($user['rank'] > 3)
                    <x-leaderboard.user-rank-row 
                        :rank="$user['rank']"
                        :username="$user['username']"
                        :avatar="$user['avatar']"
                        :netWorth="$user['net_worth']"
                        :recentBadge="$currentUserPosition['recent_badge'] ?? null"
                        :isCurrentUser="$user['is_current_user'] ?? false"
                    />
                @endif
            @empty
                <x-ui.empty-state 
                    icon="emoji_events"
                    message="Nessun trader in classifica"
                >
                    <p class="text-text-muted text-sm">Inizia a fare trading per apparire nella Dean's List!</p>
                </x-ui.empty-state>
            @endforelse
        </div>
    </div>
</x-app>