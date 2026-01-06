@use(App\Models\Utility\Notification)
@use(App\Models\Admin\MarketCommunication)

@props(['title' => null, 'active' => null, 'balance' => null, 'unreadNotifications' => null])

@php
    // Get unread notifications count if not passed
    $unreadCount = $unreadNotifications ?? (auth()->check() 
        ? Notification::forUser(auth()->id())->unread()->count() 
        : 0);
    
    // Get active market communications
    $marketCommunications = MarketCommunication::active()
        ->orderBy('expires_at', 'asc')
        ->get();
@endphp

<x-base :title="$title" :data-toast="session('toast') ? json_encode(session('toast')) : null">
    {{-- Navigation bar component (renders fixed navs) --}}
    <x-navigation.navigation-bar :active="$active ?? null" :balance="$balance ?? null" :unreadNotifications="$unreadCount" />

    {{-- Notification Slide Panel --}}
    <x-notifications.slide-panel :communications="$marketCommunications" />

    {{-- Toast notifications --}}
    <x-ui.toast />

    {{-- Top spacer: only mobile --}}
    <div class="lg:hidden h-10" aria-hidden="true"></div>
    <div class="min-h-screen pb-20 lg:pb-8 lg:pt-18">
        <main>
            {{ $slot }}
        </main>
    </div>

    @push('scripts')
        @vite(['resources/js/core/notifications.js', 'resources/js/core/toast.js'])
    @endpush
</x-base>
