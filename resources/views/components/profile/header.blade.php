@props([
    'user' => null,
])

<div class="flex flex-col items-center py-8 px-4">
    {{-- Profile Avatar --}}
    <div class="relative mb-4">
        <div class="w-32 h-32 rounded-full border-4 border-green-500 overflow-hidden bg-gray-800">
            <img 
                src="{{ $user->avatarUrl() }}" 
                alt="{{ $user->name }}"
                class="w-full h-full object-cover"
            >
        </div>
    </div>
    
    {{-- Username --}}
    <h1 class="text-2xl font-bold text-white mb-1">{{ $user->name }}</h1>
    
    {{-- Email --}}
    <p class="text-gray-400 text-sm">{{ $user->email }}</p>
</div>
