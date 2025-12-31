@extends('layouts.app')

@section('title', 'Profilo')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">
    
    {{-- Profile Header with Avatar --}}
    <div class="text-center mb-6">
        {{-- Avatar with edit button --}}
        <div class="relative inline-block mb-4">
            <div class="w-28 h-28 rounded-full border-4 border-emerald-500 p-1">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->nickname }}" class="w-full h-full rounded-full object-cover">
                @else
                    <div class="w-full h-full rounded-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                        <span class="text-4xl font-bold text-white">
                            {{ strtoupper(substr($user->nickname ?? $user->email, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>
            {{-- Edit button --}}
            <button class="absolute bottom-0 right-0 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-lg hover:bg-emerald-400 transition-colors">
                <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </button>
        </div>
        
        {{-- Name and email --}}
        <h1 class="text-2xl font-bold text-white mb-1">{{ $user->nickname ?? 'Trader' }}</h1>
        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
    </div>

    {{-- Badges Section --}}
    @if(count($badges) > 0)
    <div class="flex justify-center gap-4 mb-6 overflow-x-auto pb-2">
        @foreach($badges as $badge)
        <div class="flex flex-col items-center flex-shrink-0">
            <div class="w-14 h-14 rounded-full flex items-center justify-center mb-1
                @if($badge->slug === 'vip') bg-emerald-500/20
                @elseif($badge->slug === 'top-trader') bg-blue-500/20
                @elseif($badge->slug === 'diamond-hands') bg-yellow-500/20
                @elseif($badge->slug === 'deans-list') bg-cyan-500/20
                @else bg-gray-700
                @endif">
                @if($badge->slug === 'vip')
                    <svg class="w-7 h-7 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @elseif($badge->slug === 'top-trader')
                    <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                @elseif($badge->slug === 'diamond-hands')
                    <svg class="w-7 h-7 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                @elseif($badge->slug === 'deans-list')
                    <svg class="w-7 h-7 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                @else
                    <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                @endif
            </div>
            <span class="text-xs text-gray-400 text-center whitespace-nowrap">{{ $badge->name }}</span>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Statistiche Rapide --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Statistiche Rapide</h2>
        <div class="grid grid-cols-2 gap-3">
            {{-- Iscritto da --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-gray-500 text-xs mb-1">Iscritto da</p>
                <p class="text-white font-bold text-lg">{{ $memberSince->translatedFormat('M Y') }}</p>
            </div>
            
            {{-- Trade totali --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-gray-500 text-xs mb-1">Trade totali</p>
                <p class="text-white font-bold text-lg font-mono">{{ number_format($totalTrades, 0, ',', '.') }}</p>
            </div>
            
            {{-- Miglior Trade --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-xs mb-1">Miglior Trade</p>
                        @if($bestTrade)
                            <p class="text-emerald-500 font-bold text-lg font-mono">+{{ $bestTrade['profit_percent'] }}%</p>
                            <p class="text-gray-500 text-xs uppercase">{{ $bestTrade['ticker'] }}</p>
                        @else
                            <p class="text-gray-600 font-bold text-lg">--</p>
                        @endif
                    </div>
                    @if($bestTrade)
                    <svg class="w-10 h-10 text-emerald-500/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    @endif
                </div>
            </div>
            
            {{-- Posizione Globale --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
                <p class="text-gray-500 text-xs mb-1">Posizione</p>
                <div class="flex items-baseline gap-2">
                    @if($globalRank)
                        <p class="text-white font-bold text-lg font-mono">#{{ $globalRank }}</p>
                        <span class="text-gray-500 text-xs">Global</span>
                    @else
                        <p class="text-gray-600 font-bold text-lg">--</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Menu Opzioni --}}
    <div class="mb-6">
        <h2 class="text-lg font-semibold text-white mb-4">Menu Opzioni</h2>
        <div class="space-y-2">
            {{-- Impostazioni Account --}}
            <a href="#" class="flex items-center justify-between bg-gray-900 border border-gray-800 rounded-xl p-4 hover:bg-gray-800/50 transition-colors group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <span class="font-medium text-white">Impostazioni Account</span>
                </div>
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>

            {{-- Centro Notifiche --}}
            <a href="#" class="flex items-center justify-between bg-gray-900 border border-gray-800 rounded-xl p-4 hover:bg-gray-800/50 transition-colors group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <span class="font-medium text-white">Centro Notifiche</span>
                </div>
                <div class="flex items-center gap-2">
                    @if($unreadNotifications > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $unreadNotifications }}</span>
                    @endif
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </div>
            </a>

            {{-- Sicurezza e Privacy --}}
            <a href="#" class="flex items-center justify-between bg-gray-900 border border-gray-800 rounded-xl p-4 hover:bg-gray-800/50 transition-colors group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <span class="font-medium text-white">Sicurezza e Privacy</span>
                </div>
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>

    {{-- Logout Button --}}
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full flex items-center justify-center gap-3 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-500 font-medium px-6 py-4 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Logout
        </button>
    </form>

    {{-- App Version --}}
    <p class="text-center text-gray-600 text-xs mt-6">v2.1.0 (Build 405)</p>

</div>
@endsection
