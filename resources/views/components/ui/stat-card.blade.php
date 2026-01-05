@props([
    'title' => '',
    'value' => '',
    'color' => 'white',
])

<div class="bg-surface-100 rounded-xl p-4 border border-surface-200">
    <p class="text-text-muted text-sm mb-1">{{ $title }}</p>
    <p class="text-2xl font-bold text-{{ $color }}">{{ $value }}</p>
</div>
