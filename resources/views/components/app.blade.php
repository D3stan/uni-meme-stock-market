@use(App\Models\Utility\Notification)

@props(['title' => null, 'active' => null, 'balance' => null, 'unreadNotifications' => null])

@php
    // Get unread notifications count if not passed
    $unreadCount = $unreadNotifications ?? (auth()->check() 
        ? Notification::forUser(auth()->id())->unread()->count() 
        : 0);
@endphp

<x-base :title="$title">
    {{-- Navigation bar component (renders fixed navs) --}}
    <x-navigation.navigation-bar :active="$active ?? null" :balance="$balance ?? null" :unreadNotifications="$unreadCount" />

    {{-- Notification Slide Panel --}}
    <x-notifications.slide-panel />

    {{-- Top spacer: only mobile --}}
    <div class="lg:hidden h-10" aria-hidden="true"></div>
    <div class="min-h-screen pb-20 lg:pb-8 lg:pt-18">
        <main>
            {{ $slot }}
        </main>
    </div>

    @push('scripts')
        @vite(['resources/js/core/notifications.js'])
    @endpush
</x-base>
