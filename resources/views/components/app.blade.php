<x-base>
    {{-- Navigation bar component (renders fixed navs) --}}
    <x-navigation.navigation-bar :active="$active ?? null" :balance="$balance ?? null" />

    {{-- Top spacer: only mobile --}}
    <div class="lg:hidden h-9" aria-hidden="true"></div>

    <main>
        {{ $slot }}
    </main>
</x-base>
