@props([
    'title' => '',
    'value' => '',
    'color' => 'white',
])

<div class="bg-gray-900 rounded-xl p-4 border border-gray-800">
    <p class="text-gray-400 text-sm mb-1">{{ $title }}</p>
    <p class="text-2xl font-bold text-{{ $color }}">{{ $value }}</p>
</div>
