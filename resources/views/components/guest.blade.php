<x-base :title="$title ?? 'AlmaStreet'">
    <div class="min-h-screen flex flex-col">
        {{ $slot }}
    </div>
</x-base>
