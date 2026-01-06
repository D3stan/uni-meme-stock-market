@props(['title' => null, 'active' => null, 'balance' => null])

<x-base :title="$title">
    {{-- Navigation bar component (renders fixed navs) --}}
    <x-navigation.navigation-bar :active="$active ?? null" :balance="$balance ?? null" />

    {{-- Toast notifications --}}
    <x-ui.toast />

    {{-- Top spacer: only mobile --}}
    <div class="lg:hidden h-10" aria-hidden="true"></div>
    <div class="min-h-screen pb-20 lg:pb-8 lg:pt-18">
        <main>
            {{ $slot }}
        </main>
    </div>

    @if(session('toast'))
        @push('page-scripts')
        <script type="module">
            import NotificationService from '{{ asset('resources/js/services/NotificationService.js') }}';
            
            document.addEventListener('DOMContentLoaded', () => {
                const toast = @json(session('toast'));
                NotificationService.show(toast.message, toast.type || 'info');
            });
        </script>
        @endpush
    @endif
</x-base>
