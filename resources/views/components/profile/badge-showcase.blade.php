@props([
    'badges' => [],
])

<div class="px-4 mb-6">
    <h2 class="text-lg font-bold text-text-main mb-4">Badge Sbloccati</h2>
    
    <div class="grid grid-cols-4 gap-3">
        @foreach($badges as $badge)
            <div class="flex flex-col items-center">
                {{-- Badge Image Circle --}}
                <div class="w-16 h-16 rounded-full flex items-center justify-center mb-2 bg-surface-100 shadow-lg overflow-hidden">
                    <img 
                        src="{{ asset('storage/' . $badge->icon_path) }}" 
                        alt="{{ $badge->name }}"
                        class="w-full h-full object-contain"
                    >
                </div>
                
                {{-- Badge Name --}}
                <p class="text-xs text-center text-text-muted font-medium leading-tight">
                    {{ Str::replace(['💎', '📄', '🐋', '📈', '🍀'], '', $badge->name) }}
                </p>
            </div>
        @endforeach
    </div>
</div>
