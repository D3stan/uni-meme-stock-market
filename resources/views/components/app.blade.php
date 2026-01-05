@props(['title' => null, 'active' => null])

<x-base :title="$title">
    {{-- Navigation bar component (renders fixed navs) --}}
    <x-navigation.navigation-bar :active="$active ?? null" :balance="$balance ?? null" />

    {{-- Top spacer: only mobile --}}
    <div class="lg:hidden h-10" aria-hidden="true"></div>
    <div class="min-h-screen pb-20 lg:pb-8 lg:pt-18">
        <main>
            {{ $slot }}
        </main>
    </div>
</x-base>
