@props([
    'title' => null,
    'value' => null,
    'variation' => null
])

<div class="bg-surface-100 rounded-3xl p-6 border border-surface-200">
    <div class="flex items-start justify-between mb-4">
        <div>
            <p class="text-text-muted text-lg mb-2">{{ $title }}</p>
            <p class="text-{{ $variation ? '4xl': '7xl' }} font-bold text-text-main">{{ $value }}</p>
        </div>
    </div>
    @if ($variation)
        <x-ui.badge-change value="{{ $variation }}" size="lg" showIcon="true" />
    @endif
</div>