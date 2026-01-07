<x-base :title="$title ?? 'AlmaStreet'">
    <main class="min-h-screen flex flex-col">
        {{ $slot }}
    </main>
</x-base>
