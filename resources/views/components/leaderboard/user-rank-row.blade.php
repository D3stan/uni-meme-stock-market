@props([
    'rank' => 0,
    'username' => '',
    'avatar' => null,
    'netWorth' => 0,
    'isCurrentUser' => false,
])

<div role="listitem" class="card-base p-4 mx-4 lg:mx-auto lg:max-w-[60%] hover:bg-surface-200/50 transition-colors {{ $isCurrentUser ? 'ring-2 ring-brand' : '' }}">
    <div class="flex items-center gap-4">
        {{-- Rank Number --}}
        <div class="w-8 text-center">
            <span class="text-text-muted font-bold text-lg" aria-label="Posizione {{ $rank }}">{{ $rank }}</span>
        </div>
        
        {{-- Avatar --}}
        <div class="relative flex-shrink-0">
            <img 
                src="{{ $avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($username) }}" 
                alt="Avatar di {{ $username }}"
                class="w-12 h-12 rounded-full object-cover border-2 {{ $isCurrentUser ? 'border-brand' : 'border-surface-200' }}"
            >
        </div>
        
        {{-- User Info --}}
        <div class="flex-1 min-w-0">
            <p class="text-text-main font-bold text-base truncate">{{ $username }}</p>
        </div>
        
        {{-- Net Worth --}}
        <div class="text-right">
            <p class="text-brand font-mono font-bold text-lg whitespace-nowrap">
                {{ number_format($netWorth) }}
            </p>
            <p class="text-text-muted text-xs uppercase">CFU</p>
        </div>
    </div>
</div>