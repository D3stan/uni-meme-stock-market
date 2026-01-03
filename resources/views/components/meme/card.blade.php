@props([
    'image' => null,
    'name' => 'Meme Name',
    'ticker' => 'XXX',
    'price' => 0,
    'change' => 0,
    'creatorAvatar' => null,
    'creatorName' => 'Creator',
    'status' => null, // 'new', 'pending', null
    'tradeUrl' => '#',
])


<div {{ $attributes->merge(['class' => 'bg-gray-800 rounded-4xl overflow-hidden shadow-lg border border-gray-700']) }}>
    {{-- Header Card --}}
    <div class="flex items-center justify-between p-4">
        <div class="flex items-center gap-3">
            {{-- Avatar Creatore --}}
            <!-- TODO -> Change profile avatar -->
            <img 
                src="{{ $creatorAvatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($creatorName) . '&background=10b981&color=fff' }}" 
                alt="{{ $creatorName }}"
                class="w-8 h-8 rounded-full object-cover"
            >
            <div>
                <h3 class="text-sm font-medium text-white">{{ $name }}</h3>
                <p class="text-xs text-gray-400">${{ $ticker }}</p>
            </div>
        </div>
        
        {{-- Badge Status --}}
        @if($status)
            @php
                $statusClasses = match($status) {
                    'new' => 'bg-green-500/20 text-green-400 border-green-500/30',
                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                    default => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                };
            @endphp
            <span class="px-2 py-1 text-xs font-semibold rounded-md border {{ $statusClasses }}">
                {{ ucfirst($status) }}
            </span>
        @endif
    </div>
    
    {{-- Media (Immagine Meme) --}}
    <a href="{{ $tradeUrl }}" class="block">
        <img 
            src="{{ asset($image) }}" 
            alt="{{ $name }}"
            class="w-full h-auto hover:opacity-90 transition-opacity"
        >
    </a>
    
    {{-- Info Bar --}}
    <div class="p-4">
        <div class="flex items-end justify-between mb-4">
            {{-- Prezzo --}}
            <div>
                <p class="text-2xl font-bold font-mono text-white">{{ number_format($price, 2) }}</p>
                <p class="text-xs text-gray-400">Prezzo</p>
            </div>
            
            {{-- Badge Variazione --}}
            <x-ui.badge-change :value="$change" size="lg" />
        </div>
        
        {{-- Bottone Trade --}}
        <a href="{{ $tradeUrl }}" class="block">
            <x-forms.button variant="success" class="w-full">
                Trade
            </x-forms.button>
        </a>
    </div>
</div>
