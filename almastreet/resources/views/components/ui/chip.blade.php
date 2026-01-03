@props([
    'variant' => 'outline', // white, outline, success
    'icon' => null, // ( '🚀', '✨', '⚠️')
    'active' => false,
])

@php
    $baseClasses = 'inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer whitespace-nowrap';
    
    $variantClasses = match($variant) {
        'white' => 'bg-white text-gray-700 border border-gray-600 hover:bg-gray-600',
        'success' => 'bg-green-600 text-white border border-green-600 hover:bg-green-700',
        default => 'bg-transparent text-gray-300 border border-gray-600 hover:bg-gray-700',
    };
    
    if ($active) {
        $variantClasses = 'bg-green-600 text-white border border-green-600 hover:bg-green-700';
    }
@endphp

<button 
    {{ $attributes->merge([
        'class' => "$baseClasses $variantClasses",
        'type' => 'button',
    ]) }}
>
    @if($icon)
        <span class="text-base leading-none">{{ $icon }}</span>
    @endif
    {{ $slot }}
</button>
