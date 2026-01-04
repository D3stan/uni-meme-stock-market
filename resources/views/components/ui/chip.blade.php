@props([
    'variant' => 'outline', // white, outline, success
    'icon' => null, // ( '🚀', '✨', '⚠️')
    'active' => false,
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap';
    
    $variantClasses = match($variant) {
        'white' => 'bg-white text-gray-700 border border-gray-600 hover:bg-gray-600',
        'success' => 'bg-green-600 text-white border border-green-600 hover:bg-green-700',
        default => 'bg-transparent text-gray-300 border border-gray-600 hover:bg-gray-700',
    };
    
    if ($active) {
        $variantClasses = 'bg-green-600 text-white border border-green-600 hover:bg-green-700';
    }
    
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    {{ $attributes->merge([
        'class' => "$baseClasses $variantClasses",
        'href' => $href,
        'type' => $href ? null : 'button',
    ])->filter(fn($value) => $value !== null) }}
    @if($active) aria-current="page" @endif
>
    @if($icon)
        <span class="text-base leading-none">{{ $icon }}</span>
    @endif
    {{ $slot }}
</{{ $tag }}>
