@props([
    'title',
    'label',
    'icon' => null,
    'href' => null,
])

<x-base>
    <div class="min-h-screen pb-8 pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <header class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-white">{{ $title }}</h1>
                <a href="{{ $href }}" aria-label="{{ $label }}" class="p-2 hover:bg-gray-800 rounded-lg transition-colors">
                    <span class="material-icons text-white text-3xl" aria-hidden="true">{{ $icon }}</span>
                </a>
            </header>
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>
</x-base>