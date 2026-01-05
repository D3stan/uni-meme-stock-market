@props([
    'variant' => 'outline', // white, outline, success
    'icon' => null, // ( '🚀', '✨', '⚠️')
    'active' => false,
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap';
    
    $variantClasses = match($variant) {
        'white' => 'bg-text-main text-surface-50 border border-surface-200 hover:bg-surface-200',
        'success' => 'bg-brand text-surface-50 border border-brand hover:bg-brand-light',
        default => 'bg-transparent text-text-muted border border-surface-200 hover:bg-surface-200',
    };
    
    if ($active) {
        $variantClasses = 'bg-brand text-surface-50 border border-brand hover:bg-brand-light';
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
