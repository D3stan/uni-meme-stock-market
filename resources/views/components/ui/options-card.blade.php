@props([
    'href' => '#',
    'icon' => 'option',
    'title' => null,
    'description' => null
])

<a href="{{$href}}" {{ $attributes->merge(['class' => 'bg-gray-900 rounded-3xl p-6 border border-gray-800 hover:border-blue-600 transition-colors group']) }}>
    <div class="flex items-center gap-4 mb-3">
        <div class="bg-blue-600 rounded-full p-4 group-hover:scale-110">
            <span class="material-icons text-white text-3xl" aria-hidden="true">{{ $icon }}</span>
        </div>
    </div>
    <h3 class="text-xl font-bold text-white mb-1">{{ $title }}</h3>
    <p class="text-gray-400">{{ $description }}</p>
</a>