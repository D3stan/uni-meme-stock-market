@props([
    'variant' => 'outline', // white, outline, success
    'icon' => null, // ( '🚀', '✨', '⚠️')
    'active' => false,
])

@php
    $baseClasses = 'inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 cursor-pointer whitespace-nowrap';
    
    $variantClasses = match($variant) {
        'white' => 'bg-white text-gray-900 border border-gray-300 hover:bg-gray-50',
        'success' => 'bg-green-600 text-white border border-green-700 hover:bg-green-700',
        default => 'bg-transparent text-gray-700 border border-green-300 hover:bg-gray-100 dark:text-gray-300 dark:border-green-800 dark:hover:bg-gray-700',
    };
    
    if ($active) {
        $variantClasses = 'bg-green-700 text-white border border-green-700 hover:bg-green-800 dark:bg-green-600 dark:border-green-600';
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
