@props(['memes' => []])

{{-- Ticker Tape (Decorative - hidden from Screen Readers) --}}
<section class="bg-surface-50/80 backdrop-blur-sm border-y border-surface-200 h-10 overflow-hidden relative" 
     aria-hidden="true">
    
    {{-- Marquee Container --}}
    <div class="ticker-tape flex items-center h-full gap-6 whitespace-nowrap w-max">
        {{-- First set of items --}}
        @foreach($memes as $meme)
            <a href="{{ route('trade', $meme['id']) }}" tabindex="-1" class="inline-flex items-center gap-2 px-3 hover:bg-surface-100 transition-colors rounded group/item">
                <span class="text-text-main font-mono font-semibold text-sm group-hover/item:text-brand transition-colors">${{ $meme['ticker'] }}</span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $meme['change'] >= 0 ? 'text-brand' : 'text-brand-danger' }}">
                    <span aria-hidden="true" class="material-icons text-xs">{{ $meme['change'] >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                    <span class="sr-only">{{ $meme['change'] >= 0 ? 'In rialzo del' : 'In ribasso del' }}</span>
                    {{ abs($meme['change']) }}%
                </span>
            </a>
            <span class="text-text-muted" aria-hidden="true">•</span>
        @endforeach
        
        {{-- Duplicate for seamless loop --}}
        @foreach($memes as $meme)
            <a href="{{ route('trade', $meme['id']) }}" tabindex="-1" aria-hidden="true" class="inline-flex items-center gap-2 px-3 hover:bg-surface-100 transition-colors rounded group/item">
                <span class="text-text-main font-mono font-semibold text-sm group-hover/item:text-brand transition-colors">${{ $meme['ticker'] }}</span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold {{ $meme['change'] >= 0 ? 'text-brand' : 'text-brand-danger' }}">
                    <span class="material-icons text-xs">{{ $meme['change'] >= 0 ? 'arrow_upward' : 'arrow_downward' }}</span>
                    {{ abs($meme['change']) }}%
                </span>
            </a>
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