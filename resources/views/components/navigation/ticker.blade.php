@props(['memes' => []])

<section aria-label="Ticker di mercato" class="bg-surface-50/80 backdrop-blur-sm border-y border-surface-200 h-10 overflow-hidden relative">
    {{-- Marquee Container --}}
    <div class="ticker-tape flex items-center h-full gap-6 whitespace-nowrap w-max">
        {{-- First set of items --}}
        @foreach($memes as $meme)
            <div class="inline-flex items-center gap-2 px-3">
                <span class="text-text-main font-mono font-semibold text-sm">${{ $meme['ticker'] }}</span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $meme['change'] >= 0 ? 'text-brand' : 'text-brand-danger' }}">
                    <span aria-hidden="true" class="material-icons text-xs">{{ $meme['change'] >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                    <span class="sr-only">{{ $meme['change'] >= 0 ? 'In rialzo del' : 'In ribasso del' }}</span>
                    {{ abs($meme['change']) }}%
                </span>
            </div>
            <span class="text-text-muted" aria-hidden="true">•</span>
        @endforeach
        
        {{-- Duplicate for seamless loop --}}
        @foreach($memes as $meme)
            <div class="inline-flex items-center gap-2 px-3" aria-hidden="true">
                <span class="text-text-main font-mono font-semibold text-sm">${{ $meme['ticker'] }}</span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $meme['change'] >= 0 ? 'text-brand' : 'text-brand-danger' }}">
                    <span class="material-icons text-xs">{{ $meme['change'] >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                    {{ abs($meme['change']) }}%
                </span>
            </div>
            <span class="text-text-muted" aria-hidden="true">•</span>
        @endforeach
    </div>
</section>

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