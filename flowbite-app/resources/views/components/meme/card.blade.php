@props([
    'meme',
    'showTradeButton' => true,
])

@php
    $change = $meme->change_24h ?? 0;
    $isPositive = $change >= 0;
    $creatorInitials = strtoupper(substr($meme->creator->username ?? $meme->creator->email ?? 'AN', 0, 2));
    
    // Generate a consistent color based on creator id
    $colors = ['bg-pink-500', 'bg-purple-500', 'bg-blue-500', 'bg-cyan-500', 'bg-teal-500', 'bg-orange-500', 'bg-red-500', 'bg-yellow-500'];
    $colorIndex = $meme->creator_id % count($colors);
    $avatarColor = $colors[$colorIndex];
@endphp

<div class="bg-gray-900/50 border border-gray-800 rounded-2xl overflow-hidden">
    {{-- Header with creator info --}}
    <div class="flex items-center justify-between p-4 pb-3">
        <div class="flex items-center gap-3">
            {{-- Creator Avatar --}}
            <div class="w-10 h-10 {{ $avatarColor }} rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-bold text-white">{{ $creatorInitials }}</span>
            </div>
            
            {{-- Meme Title & Ticker --}}
            <div>
                <h3 class="font-semibold text-white leading-tight">{{ $meme->title }}</h3>
                <p class="text-sm text-emerald-500 font-mono">${{ $meme->ticker }}</p>
            </div>
        </div>
        
        {{-- More Options --}}
        <button class="p-2 text-gray-500 hover:text-gray-300 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
            </svg>
        </button>
    </div>
    
    {{-- Meme Image --}}
    <div class="relative aspect-square bg-gray-800">
        @if($meme->image_path)
            <img 
                src="{{ asset('storage/memes/' . $meme->image_path) }}" 
                alt="{{ $meme->title }}"
                class="w-full h-full object-cover"
                loading="lazy"
            >
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-800">
                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif
    </div>
    
    {{-- Price Info --}}
    <div class="p-4">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="text-xs text-gray-500 mb-1">Current Price</p>
                <div class="flex items-baseline gap-2">
                    <span class="font-mono text-2xl font-bold text-white">{{ number_format($meme->current_price, 2) }}</span>
                    <span class="text-gray-400 text-sm">CFU</span>
                </div>
            </div>
            
            <div class="text-right">
                {{-- Change Badge --}}
                <div class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-sm font-medium {{ $isPositive ? 'bg-emerald-500/20 text-emerald-500' : 'bg-red-500/20 text-red-500' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($isPositive)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                        @endif
                    </svg>
                    <span>{{ $isPositive ? '+' : '' }}{{ number_format($change, 1) }}%</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">24h Vol</p>
            </div>
        </div>
        
        {{-- Trade Button --}}
        @if($showTradeButton)
            <a href="{{ route('meme.show', $meme->id) }}" 
               class="block w-full text-center bg-transparent border-2 border-emerald-500 text-emerald-500 hover:bg-emerald-500 hover:text-gray-900 font-semibold py-3 rounded-xl transition-all duration-200">
                Trade ${{ $meme->ticker }}
            </a>
        @endif
    </div>
</div>
