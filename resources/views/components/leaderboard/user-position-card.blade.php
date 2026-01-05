@props([
    'rank' => 0,
    'username' => '',
    'avatar' => null,
    'netWorth' => 0,
    'percentile' => null,
    'hasBadge' => false,
])

<div class="card-base p-4 bg-gradient-to-r from-brand/20 to-brand/5 border-2 border-brand shadow-lg shadow-brand/20">
    {{-- Header with icon --}}
    <div class="flex items-center gap-2 mb-3">
        <span class="material-icons text-brand text-sm">gps_fixed</span>
        <span class="text-brand font-bold text-xs uppercase tracking-wider">La Tua Posizione</span>
        @if($percentile)
        <span class="ml-auto text-text-muted text-xs font-bold">Top {{ $percentile }}%</span>
        @endif
    </div>
    
    {{-- User Info --}}
    <div class="flex items-center gap-4">
        {{-- Rank Number --}}
        <div class="text-center">
            <span class="text-text-main font-black text-3xl">{{ $rank }}</span>
        </div>
        
        {{-- Avatar with badge --}}
        <div class="relative flex-shrink-0">
            <img 
                src="{{ $avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($username) }}" 
                alt="{{ $username }}"
                class="w-16 h-16 rounded-full object-cover border-4 border-brand"
            >
            @if($hasBadge)
            <div class="absolute -bottom-1 -right-1 bg-brand text-surface-50 px-2 py-0.5 rounded-full text-xs font-bold uppercase">
                PRO
            </div>
            @endif
        </div>
        
        {{-- Username and Title --}}
        <div class="flex-1 min-w-0">
            <p class="text-text-main font-black text-lg truncate">Tu</p>
            <p class="text-text-muted text-sm truncate">{{ $username }}</p>
        </div>
        
        {{-- Net Worth --}}
        <div class="text-right">
            <p class="text-brand font-mono font-black text-2xl whitespace-nowrap">
                {{ number_format($netWorth) }}
            </p>
            <p class="text-text-muted text-xs uppercase font-bold">CFU Net Worth</p>
        </div>
    </div>
</div>
