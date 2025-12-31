<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AlmaStreet') }} - @yield('title', 'Market')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&family=jetbrains-mono:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white antialiased">
    <div class="min-h-screen flex flex-col">
        
        {{-- Top Bar --}}
        <header class="fixed top-0 left-0 right-0 z-50 safe-top bg-gray-950 border-b border-gray-800/50">
            <div class="flex items-center justify-between h-14 px-4">
                {{-- Logo --}}
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                
                {{-- CFU Balance --}}
                <div class="flex items-center">
                    <span class="font-mono font-bold text-lg text-white">{{ number_format(auth()->user()->cfu_balance, 2) }}</span>
                    <span class="ml-1 text-emerald-500 font-bold text-sm">CFU</span>
                </div>
                
                {{-- Notifications Bell --}}
                <button class="relative p-2 text-gray-400 hover:text-white transition-colors" id="notifications-btn">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    {{-- Notification Badge --}}
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-gray-950"></span>
                    @endif
                </button>
            </div>
            
            {{-- Desktop Navigation (hidden on mobile) --}}
            <nav class="hidden lg:block border-t border-gray-800/50">
                <div class="max-w-5xl mx-auto px-4">
                    <div class="flex items-center justify-center space-x-1">
                        <a href="{{ route('marketplace') }}" 
                           class="flex items-center gap-2 px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('marketplace') ? 'text-emerald-500 border-b-2 border-emerald-500' : 'text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Market
                        </a>
                        <a href="{{ route('leaderboard') }}" 
                           class="flex items-center gap-2 px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('leaderboard') ? 'text-emerald-500 border-b-2 border-emerald-500' : 'text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Classifica
                        </a>
                        <a href="{{ route('portfolio') }}" 
                           class="flex items-center gap-2 px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('portfolio') ? 'text-emerald-500 border-b-2 border-emerald-500' : 'text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            Portafoglio
                        </a>
                        <a href="{{ route('profile') }}" 
                           class="flex items-center gap-2 px-6 py-3 text-sm font-medium transition-colors {{ request()->routeIs('profile') ? 'text-emerald-500 border-b-2 border-emerald-500' : 'text-gray-400 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profilo
                        </a>
                        <a href="{{ route('meme.create') }}" 
                           class="flex items-center gap-2 ml-4 px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-gray-900 text-sm font-semibold rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nuovo
                        </a>
                    </div>
                </div>
            </nav>
        </header>

        {{-- Main Content Area --}}
        <main class="flex-1 pt-14 pb-24 lg:pt-28 lg:pb-8">
            @yield('content')
        </main>

        {{-- Mobile Bottom Navigation Bar (hidden on lg screens and above) --}}
        <nav class="mobile-bottom-nav fixed bottom-0 left-0 right-0 z-50 bg-gray-950 border-t border-gray-800/50" style="padding-bottom: env(safe-area-inset-bottom, 0px);">
            <div class="flex items-center justify-around h-16 px-2 relative w-full">
                {{-- Market --}}
                <a href="{{ route('marketplace') }}" class="flex flex-col items-center justify-center flex-1 py-2 group">
                    <svg class="w-6 h-6 {{ request()->routeIs('marketplace') ? 'text-emerald-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="text-xs mt-1 {{ request()->routeIs('marketplace') ? 'text-emerald-500 font-medium' : 'text-gray-500' }}">Market</span>
                    @if(request()->routeIs('marketplace'))
                        <span class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-emerald-500 rounded-full" style="left: 12.5%;"></span>
                    @endif
                </a>
                
                {{-- Classifica --}}
                <a href="{{ route('leaderboard') }}" class="flex flex-col items-center justify-center flex-1 py-2 group">
                    <svg class="w-6 h-6 {{ request()->routeIs('leaderboard') ? 'text-emerald-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="text-xs mt-1 {{ request()->routeIs('leaderboard') ? 'text-emerald-500 font-medium' : 'text-gray-500' }}">Classifica</span>
                </a>
                
                {{-- Create Button (Elevated) --}}
                <div class="flex-1 flex justify-center">
                    <a href="{{ route('meme.create') }}" class="w-12 h-12 bg-emerald-500 hover:bg-emerald-400 rounded-full flex items-center justify-center shadow-lg shadow-emerald-500/30 transition-all transform hover:scale-105">
                        <svg class="w-6 h-6 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                    </a>
                </div>
                
                {{-- Portafoglio --}}
                <a href="{{ route('portfolio') }}" class="flex flex-col items-center justify-center flex-1 py-2 group">
                    <svg class="w-6 h-6 {{ request()->routeIs('portfolio') ? 'text-emerald-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="text-xs mt-1 {{ request()->routeIs('portfolio') ? 'text-emerald-500 font-medium' : 'text-gray-500' }}">Portafoglio</span>
                </a>
                
                {{-- Profilo --}}
                <a href="{{ route('profile') }}" class="flex flex-col items-center justify-center flex-1 py-2 group">
                    <svg class="w-6 h-6 {{ request()->routeIs('profile') ? 'text-emerald-500' : 'text-gray-500 group-hover:text-gray-300' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-xs mt-1 {{ request()->routeIs('profile') ? 'text-emerald-500 font-medium' : 'text-gray-500' }}">Profilo</span>
                </a>
            </div>
        </nav>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.js"></script>
</body>
</html>
