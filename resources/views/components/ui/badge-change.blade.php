@props([
    'value' => 0,
    'showIcon' => true,
    'size' => 'md', // sm, md, lg
])

@php
    $isPositive = $value > 0;
    $isNegative = $value < 0;
    $isNeutral = $value == 0;
    
    $formattedValue = $isPositive ? '+' . number_format($value, 2) : number_format($value, 2);
    
    $sizeClasses = match($size) {
        'sm' => 'text-xs px-2 py-0.5',
        'lg' => 'text-base px-3 py-1',
        default => 'text-sm px-2.5 py-0.5',
    };
    
    $colorClasses = match(true) {
        $isPositive => 'badge-positive',
        $isNegative => 'badge-negative',
        default => 'badge-neutral',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1 font-medium rounded $sizeClasses $colorClasses"]) }}>
    @if($showIcon)
        @if($isPositive)
            {{-- Freccia su --}}
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
            </svg>
        @elseif($isNegative)
            {{-- Freccia giù --}}
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
        @else
            {{-- Linea neutra --}}
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
            </svg>
        @endif
    @endif
    {{ $formattedValue }}%
</span>
