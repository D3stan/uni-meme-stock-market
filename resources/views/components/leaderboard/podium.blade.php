@props([
    'first' => null,
    'second' => null,
    'third' => null,
])

<section aria-label="Podio dei vincitori" class="relative px-4 py-8">
    <div class="flex items-end justify-center gap-4">
        {{-- Second Place (Left) --}}
        @if($second)
        <div class="flex flex-col items-center w-32">
            {{-- Avatar with position badge --}}
            <div class="relative mb-3">
                <img 
                    src="{{ $second['avatar'] }}" 
                    alt="Avatar di {{ $second['username'] }}, 2° posto"
                    class="w-20 h-20 rounded-full border-4 border-surface-200 object-cover"
                >
                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-surface-100 border-2 border-surface-200 rounded-full w-10 h-10 flex items-center justify-center" aria-hidden="true">
                    <span class="text-text-muted font-bold text-lg">2°</span>
                </div>
            </div>
            
            {{-- Username --}}
            <p class="text-text-main font-bold text-sm mb-1">{{ $second['username'] }}</p>
            
            {{-- Net Worth --}}
            <p class="text-brand font-mono font-bold text-lg" aria-label="Patrimonio: {{ number_format($second['net_worth'] / 1000, 1) }} mila CFU">
                {{ number_format($second['net_worth'] / 1000, 1) }}k
            </p>
        </div>
        @endif

        {{-- First Place (Center, Elevated) --}}
        @if($first)
        <div class="flex flex-col items-center w-36 -mt-6">
            {{-- Trophy Background --}}
            <div class="relative mb-3">
                {{-- Gold Trophy Circle --}}
                <div class="absolute inset-0 bg-gradient-to-b from-yellow-500 to-yellow-700 rounded-full blur-xl opacity-50" aria-hidden="true"></div>
                <div class="relative bg-gradient-to-b from-yellow-400 to-yellow-600 rounded-full p-1">
                    <img 
                        src="{{ $first['avatar'] }}" 
                        alt="Avatar di {{ $first['username'] }}, 1° posto"
                        class="w-24 h-24 rounded-full border-4 border-yellow-300 object-cover"
                    >
                </div>
                <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-yellow-500 border-2 border-yellow-300 rounded-full w-12 h-12 flex items-center justify-center shadow-lg" aria-hidden="true">
                    <span class="text-yellow-900 font-black text-xl">1°</span>
                </div>
            </div>
            
            {{-- Username --}}
            <p class="text-text-main font-black text-base mb-1">{{ $first['username'] }}</p>
            
            {{-- Net Worth --}}
            <p class="text-brand font-mono font-black text-xl" aria-label="Patrimonio: {{ number_format($first['net_worth'] / 1000, 1) }} mila CFU">
                {{ number_format($first['net_worth'] / 1000, 1) }}k
            </p>
        </div>
        @endif

        {{-- Third Place (Right) --}}
        @if($third)
        <div class="flex flex-col items-center w-32">
            {{-- Avatar with position badge --}}
            <div class="relative mb-3">
                <img 
                    src="{{ $third['avatar']}}" 
                    alt="Avatar di {{ $third['username'] }}, 3° posto"
                    class="w-20 h-20 rounded-full border-4 border-amber-700/30 object-cover"
                >
                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-surface-100 border-2 border-amber-700/50 rounded-full w-10 h-10 flex items-center justify-center" aria-hidden="true">
                    <span class="text-amber-600 font-bold text-lg">3°</span>
                </div>
            </div>
            
            {{-- Username --}}
            <p class="text-text-main font-bold text-sm mb-1">{{ $third['username'] }}</p>
            
            {{-- Net Worth --}}
            <p class="text-brand font-mono font-bold text-lg" aria-label="Patrimonio: {{ number_format($third['net_worth'] / 1000, 1) }} mila CFU">
                {{ number_format($third['net_worth'] / 1000, 1) }}k
            </p>
        </div>
        @endif
    </div>
    
    {{-- Podium Base Visual (Optional) --}}
    <div class="flex items-end justify-center gap-4 mt-4" aria-hidden="true">
        @if($second)
        <div class="w-32 h-24 bg-gradient-to-b from-surface-100 to-surface-200 rounded-t-lg flex items-center justify-center">
            <span class="text-6xl font-black text-surface-200/50">2</span>
        </div>
        @endif
        
        @if($first)
        <div class="w-36 h-32 bg-gradient-to-b from-yellow-900/30 to-yellow-950/50 rounded-t-lg flex items-center justify-center">
            <span class="text-7xl font-black text-yellow-900/30">1</span>
        </div>
        @endif
        
        @if($third)
        <div class="w-32 h-20 bg-gradient-to-b from-amber-900/20 to-amber-950/30 rounded-t-lg flex items-center justify-center">
            <span class="text-6xl font-black text-amber-900/20">3</span>
        </div>
        @endif
    </div>
</section>