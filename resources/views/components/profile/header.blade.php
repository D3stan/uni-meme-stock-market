@props([
    'user' => null,
])

<div class="flex flex-col items-center py-8 px-4">
    {{-- Profile Avatar --}}
    <div class="relative mb-4">
        <div class="w-32 h-32 rounded-full border-4 border-brand overflow-hidden bg-surface-100">
            <img 
                src="{{ $user->avatarUrl() }}" 
                alt="{{ $user->name }}"
                class="w-full h-full object-cover"
            >
        </div>
    </div>
    
    {{-- Username --}}
    <h1 class="text-2xl font-bold text-text-main mb-1">{{ $user->name }}</h1>
    
    {{-- Email --}}
    <p class="text-text-muted text-sm">{{ $user->email }}</p>
</div>
