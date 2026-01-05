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
        'danger' => 'bg-brand-danger/20 group-hover:bg-brand-danger/30',
        'disabled' => 'bg-surface-200/50',
        default => 'bg-surface-200 group-hover:bg-surface-200/80',
    };
    
    $iconColorClass = match($variant) {
        'danger' => 'text-brand-danger',
        'disabled' => 'text-text-muted',
        default => 'text-text-main',
    };
    
    $labelColorClass = match($variant) {
        'danger' => 'text-brand-danger font-medium',
        'disabled' => 'text-text-muted font-medium',
        default => 'text-text-main font-medium',
    };
    
    $borderHoverClass = match($variant) {
        'danger' => 'hover:border-brand-danger',
        'disabled' => '',
        default => 'hover:border-surface-200',
    };
    
    $chevronColorClass = match($variant) {
        'danger' => 'text-brand-danger group-hover:text-brand-danger',
        'disabled' => 'text-text-muted',
        default => 'text-text-muted group-hover:text-text-main',
    };
    
    $baseClasses = 'w-full bg-surface-100 rounded-2xl p-5 border border-surface-200 transition-colors flex items-center justify-between group';
    
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
