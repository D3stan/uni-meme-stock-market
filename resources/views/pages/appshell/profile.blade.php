<x-app :active="'profile'" :balance="$balance">
    <div class="max-w-2xl mx-auto pb-6">
        
        {{-- Profile Header (Avatar + Name + Email) --}}
        <x-profile.header :user="$user" />
        
        {{-- Badge Showcase --}}
        @if($badges->isNotEmpty())
            <x-profile.badge-showcase :badges="$badges" />
        @endif
        
        {{-- Quick Stats Grid --}}
        <x-profile.stats-grid 
            :registrationDate="$registrationDate"
            :totalTrades="$totalTrades"
            :badgeCount="$badgeCount"
            :memeCount="$memeCount"
        />
        
        {{-- Menu Options --}}
        <x-profile.menu-options :unreadNotifications="$unreadNotifications" :isAdmin="$isAdmin"/>
        
        {{-- Version Footer 
        <div class="text-center mt-8 mb-4">
            <p class="text-text-muted text-sm">v1.1.0 (Build 405)</p>
        </div>--}}
        
    </div>
</x-app>