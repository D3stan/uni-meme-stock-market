@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, success, danger, outline
    'size' => 'md', // sm, md, lg
    'disabled' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg focus:ring-4 focus:outline-none transition-colors';
    
    $variantClasses = match($variant) {
        'primary' => 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-800',
        'secondary' => 'bg-gray-800 text-white border border-gray-600 hover:bg-gray-700 hover:border-gray-600 focus:ring-gray-700',
        'success' => 'text-white bg-green-600 hover:bg-green-700 focus:ring-green-800',
        'danger' => 'text-white bg-red-600 hover:bg-red-700 focus:ring-red-900',
        'outline' => 'border border-blue-500 text-blue-500 hover:text-white hover:bg-blue-500 focus:ring-blue-800',
        default => 'text-white bg-blue-600 hover:bg-blue-700 focus:ring-blue-800',
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
