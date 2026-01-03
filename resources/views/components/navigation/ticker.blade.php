@props(['memes' => []])

<div class="bg-black backdrop-blur-sm border-y border-gray-800/50 h-10 overflow-hidden relative">
    {{-- Marquee Container --}}
    <div class="ticker-tape flex items-center h-full gap-6 whitespace-nowrap w-max">
        {{-- First set of items --}}
        @foreach($memes as $meme)
            <div class="inline-flex items-center gap-2 px-3">
                <span class="text-white font-mono font-semibold text-sm">${{ $meme['ticker'] }}</span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $meme['change'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    <span class="material-icons text-xs">{{ $meme['change'] >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                    {{ abs($meme['change']) }}%
                </span>
            </div>
            <span class="text-gray-600">•</span>
        @endforeach
        
        {{-- Duplicate for seamless loop --}}
        @foreach($memes as $meme)
            <div class="inline-flex items-center gap-2 px-3">
                <span class="text-white font-mono font-semibold text-sm">${{ $meme['ticker'] }}</span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $meme['change'] >= 0 ? 'text-green-500' : 'text-red-500' }}">
                    <span class="material-icons text-xs">{{ $meme['change'] >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                    {{ abs($meme['change']) }}%
                </span>
            </div>
            <span class="text-gray-600">•</span>
        @endforeach
    </div>
</div>

<style>
    @keyframes ticker-scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }
    
    .ticker-tape {
        animation: ticker-scroll 80s linear infinite;
        padding-left: 0;
    }
    
</style>
