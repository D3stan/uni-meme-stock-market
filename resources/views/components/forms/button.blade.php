@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, success, danger, outline, outline-neon
    'size' => 'md', // sm, md, lg
    'disabled' => false,
    'rounded' => '2xl', // 'full', '2xl', 'xl', 'lg'
])

@php
    $roundedClass = match($rounded) {
        'full' => 'rounded-full',
        'xl' => 'rounded-xl',
        'lg' => 'rounded-lg',
        default => 'rounded-2xl',
    };
    
    $baseClasses = "inline-flex items-center justify-center gap-2 font-medium $roundedClass focus:ring-4 focus:outline-none transition-colors";
    
    $variantClasses = match($variant) {
        'primary' => 'text-text-main bg-brand hover:bg-brand-light focus:ring-brand/50',
        'secondary' => 'bg-surface-200 text-text-main border border-surface-200 hover:bg-surface-200/80 focus:ring-surface-200/50',
        'success' => 'text-text-main bg-brand hover:bg-brand-light focus:ring-brand/50',
        'danger' => 'text-text-main bg-brand-danger hover:bg-brand-danger-dark focus:ring-brand-danger/50',
        'outline' => 'border border-brand text-brand hover:text-text-main hover:bg-brand focus:ring-brand/50',
        'outline-neon' => 'border-2 border-brand-neon bg-surface-100 text-brand-neon hover:bg-surface-100/80 focus:ring-brand-neon/50 shadow-lg shadow-brand-neon/20',
        default => 'text-text-main bg-brand hover:bg-brand-light focus:ring-brand/50',
    };
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-2 text-sm',
        'lg' => 'px-5 py-3 text-base',
        default => 'px-5 py-2.5 text-sm',
    };
    
    $disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
@endphp

<button 
    type="{{ $type }}"
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses $disabledClasses"]) }}
>
    {{ $slot }}
</button>
