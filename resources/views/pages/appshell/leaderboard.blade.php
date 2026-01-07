<x-app :active="'leaderboard'" :balance="$balance">
    <div class="max-w-7xl mx-auto px-4 py-6 space-y-6">
        
        {{-- Hero Section: Title --}}
        <header class="text-center space-y-2 py-4">
            <h1 class="text-4xl font-black text-text-main">Dean's List</h1>
            <p class="text-text-muted text-base">I migliori trader dell'ateneo</p>
        </header>
        
        {{-- Time Period Filters --}}
        <nav aria-label="Filtri temporali" class="flex gap-3 overflow-x-auto hide-scrollbar pb-2 justify-center">
            <button 
                onclick="filterLeaderboard('all')" 
                data-filter="all"
                class="filter-chip px-6 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all bg-brand text-surface-50 shadow-lg shadow-brand/20"
            >
                Tutti
            </button>
            <button 
                onclick="filterLeaderboard('week')" 
                data-filter="week"
                class="filter-chip px-6 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all bg-surface-200 text-text-main hover:bg-surface-200/80"
            >
                Questa Settimana
            </button>
            <button 
                onclick="filterLeaderboard('month')" 
                data-filter="month"
                class="filter-chip px-6 py-2.5 rounded-full font-bold text-sm whitespace-nowrap transition-all bg-surface-200 text-text-main hover:bg-surface-200/80"
            >
                Questo Mese
            </button>
        </div>
        
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
        
        {{-- Current User Position (if not in top visible) --}}
        @if(isset($currentUserPosition) && $currentUserPosition && ($currentUserPosition['rank'] ?? 0) > 10)
            <div class="pt-6">
                <x-leaderboard.user-position-card 
                    :rank="$currentUserPosition['rank']"
                    :username="$currentUserPosition['username']"
                    :avatar="$currentUserPosition['avatar']"
                    :netWorth="$currentUserPosition['net_worth']"
                    :percentile="$currentUserPosition['percentile'] ?? null"
                    :hasBadge="$currentUserPosition['has_badge'] ?? false"
                />
            </div>
        @endif
        
    </div>
    
    @push('page-scripts')
        <script>
            function filterLeaderboard(period) {
                // Update active state on chips
                document.querySelectorAll('.filter-chip').forEach(chip => {
                    const filter = chip.getAttribute('data-filter');
                    if (filter === period) {
                        chip.classList.remove('bg-surface-200', 'text-text-main', 'hover:bg-surface-200/80');
                        chip.classList.add('bg-brand', 'text-surface-50', 'shadow-lg', 'shadow-brand/20');
                    } else {
                        chip.classList.remove('bg-brand', 'text-surface-50', 'shadow-lg', 'shadow-brand/20');
                        chip.classList.add('bg-surface-200', 'text-text-main', 'hover:bg-surface-200/80');
                    }
                });
                
                // TODO: Fetch filtered data from server
                console.log('Filtering by:', period);
            }
        </script>
    @endpush
</x-app>