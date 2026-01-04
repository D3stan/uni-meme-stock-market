@props([
    'title' => null,
    'value' => null,
    'variation' => null
])

<div class="bg-gray-900 rounded-3xl p-6 border border-gray-800">
    <div class="flex items-start justify-between mb-4">
        <div>
            <p class="text-gray-400 text-lg mb-2">{{ $title }}</p>
            <p class="text-4xl font-bold text-white">{{ $value }}</p>
        </div>
    </div>
    <x-ui.badge-change value="{{ $variation }}" size="lg" showIcon="true" />
</div>