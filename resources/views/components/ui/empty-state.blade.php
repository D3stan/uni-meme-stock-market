@props([
    'icon' => 'inbox',
    'title' => null,
    'message' => 'Nessun dato disponibile',
])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-12 px-4']) }}>
    {{-- Icon --}}
    <div class="w-16 h-16 rounded-full bg-gray-800 flex items-center justify-center mb-4">
        <span class="material-icons text-gray-400 text-4xl">{{ $icon }}</span>
    </div>
    
    {{-- Title (optional) --}}
    @if($title)
        <h3 class="text-lg font-semibold text-white mb-2">{{ $title }}</h3>
    @endif
    
    {{-- Message --}}
    <p class="text-sm text-gray-400 text-center max-w-sm">{{ $message }}</p>
    
    {{-- Slot for additional content (e.g., CTA button) --}}
    @if($slot->isNotEmpty())
        <div class="mt-6">
            {{ $slot }}
        </div>
    @endif
</div>
