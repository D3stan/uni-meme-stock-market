@props([
    'image' => null,
    'alt',
    'name' => 'Meme Name',
    'ticker' => 'XXX',
    'price' => 0,
    'change' => 0,
    'creatorAvatar' => null,
    'creatorName' => 'Creator',
    'status' => null, // 'new', 'pending', null
    'tradeUrl' => '#',
])


<article {{ $attributes->merge(['class' => 'card-base']) }}>
    {{-- Header Card --}}
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center gap-3">
            {{-- Avatar Creatore --}}
            <img 
                src="{{ $creatorAvatar }}" 
                alt="Creatore: {{ $creatorName }}"
                class="w-8 h-8 rounded-full object-cover"
            >
            <div>
                <h3 class="text-sm font-medium text-text-main">{{ $name }}</h3>
                <p class="text-xs text-text-muted">${{ $ticker }}</p>
            </div>
        </div>
        
        {{-- Badge Status --}}
        @if($status)
            @php
                $statusClasses = match($status) {
                    'new' => 'badge-positive',
                    'pending' => 'badge-info',
                    default => 'badge-neutral',
                };
            @endphp
            <span class="{{ $statusClasses }}" aria-label="Stato: {{ $status }}">
                {{ ucfirst($status) }}
            </span>
        @endif
    </div>
    
    {{-- Media (Immagine Meme) --}}
    <a href="{{ $tradeUrl }}" class="block" aria-hidden="true" tabindex="-1">
        <img 
            src="{{ asset($image) }}" 
            alt="{{ $alt }}"
            class="w-full h-auto hover:opacity-90 transition-opacity"
        >
    </a>
    
    {{-- Info Bar --}}
    <div class="p-4">
        <div class="flex items-end justify-between mb-4">
            {{-- Prezzo --}}
            <div>
                <p class="text-2xl font-bold font-mono text-text-main">{{ number_format($price, 2) }} <span class="sr-only">CFU</span></p>
                <p class="text-xs text-text-muted">Prezzo</p>
            </div>
            
            {{-- Badge Variazione --}}
            <x-ui.badge-change :value="$change" size="lg" />
        </div>
        
        {{-- Bottone Trade --}}
        <a href="{{ $tradeUrl }}" class="block w-full">
            <x-forms.button variant="primary" class="w-full">
                Trade {{ $name }}
            </x-forms.button>
        </a>
    </div>
</article>