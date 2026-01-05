@props([
    'href' => '#',
    'icon' => 'option',
    'title' => null,
    'description' => null
])

<a href="{{$href}}" {{ $attributes->merge(['class' => 'bg-surface-100 rounded-3xl p-6 border border-surface-200 hover:border-brand transition-colors group']) }}>
    <div class="flex items-center gap-4 mb-3">
        <div class="bg-brand rounded-full p-4 group-hover:scale-110">
            <span class="material-icons text-text-main text-3xl" aria-hidden="true">{{ $icon }}</span>
        </div>
    </div>
    <h3 class="text-xl font-bold text-text-main mb-1">{{ $title }}</h3>
    <p class="text-text-muted">{{ $description }}</p>
</a>