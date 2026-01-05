@props([
    'icon' => 'settings',
    'label' => '',
    'sublabel' => '',
    'action' => '#',
    'type' => 'link',
    'variant' => 'default',
    'disabled' => false,
    'onclick' => null,
])

@php
    $iconBgClass = match($variant) {
        'danger' => 'bg-red-900/20 group-hover:bg-red-900/30',
        'disabled' => 'bg-gray-800/50',
        default => 'bg-gray-800 group-hover:bg-gray-700',
    };
    
    $iconColorClass = match($variant) {
        'danger' => 'text-red-500',
        'disabled' => 'text-gray-600',
        default => 'text-white',
    };
    
    $labelColorClass = match($variant) {
        'danger' => 'text-red-500 font-medium',
        'disabled' => 'text-gray-600 font-medium',
        default => 'text-white font-medium',
    };
    
    $borderHoverClass = match($variant) {
        'danger' => 'hover:border-red-900',
        'disabled' => '',
        default => 'hover:border-gray-700',
    };
    
    $chevronColorClass = match($variant) {
        'danger' => 'text-red-500 group-hover:text-red-400',
        'disabled' => 'text-gray-700',
        default => 'text-gray-600 group-hover:text-gray-400',
    };
    
    $baseClasses = 'w-full bg-gray-900 rounded-2xl p-5 border border-gray-800 transition-colors flex items-center justify-between group';
    
    if ($disabled) {
        $baseClasses .= ' cursor-not-allowed opacity-60';
    } else {
        $baseClasses .= ' ' . $borderHoverClass;
    }
@endphp

@if($type === 'link' && !$disabled)
    <a href="{{ $action }}" class="{{ $baseClasses }}">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 {{ $iconBgClass }} rounded-full flex items-center justify-center transition-colors">
                <span class="material-icons {{ $iconColorClass }} text-xl">{{ $icon }}</span>
            </div>
            <div>
                <span class="{{ $labelColorClass }}">{{ $label }}</span>
                @if($sublabel)
                    <p class="text-gray-600 text-sm mt-0.5">{{ $sublabel }}</p>
                @endif
            </div>
        </div>
        <span class="material-icons {{ $chevronColorClass }} transition-colors">chevron_right</span>
    </a>
@else
    <button 
        type="button"
        @if($onclick) onclick="{{ $onclick }}" @endif
        @if($disabled) disabled @endif
        class="{{ $baseClasses }}"
    >
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 {{ $iconBgClass }} rounded-full flex items-center justify-center transition-colors">
                <span class="material-icons {{ $iconColorClass }} text-xl">{{ $icon }}</span>
            </div>
            <div>
                <span class="{{ $labelColorClass }}">{{ $label }}</span>
                @if($sublabel)
                    <p class="{{ $disabled ? 'text-gray-700' : 'text-gray-600' }} text-sm mt-0.5">{{ $sublabel }}</p>
                @endif
            </div>
        </div>
        <span class="material-icons {{ $chevronColorClass }} transition-colors">chevron_right</span>
    </button>
@endif
