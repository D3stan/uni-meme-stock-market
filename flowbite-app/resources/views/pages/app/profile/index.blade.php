@extends('layouts.app')

@section('title', 'Profilo')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">
    
    {{-- Profile Header --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-6 mb-6">
        <div class="flex items-start gap-4">
            {{-- Avatar --}}
            <div class="w-20 h-20 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-3xl font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->username ?? auth()->user()->email, 0, 1)) }}
                </span>
            </div>
            
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-bold text-white truncate">{{ auth()->user()->username ?? 'Utente' }}</h1>
                <p class="text-gray-500 text-sm truncate">{{ auth()->user()->email }}</p>
                
                {{-- Stats Row --}}
                <div class="flex items-center gap-4 mt-3">
                    <div class="text-center">
                        <p class="font-mono font-bold text-white">0</p>
                        <p class="text-xs text-gray-500">Trades</p>
                    </div>
                    <div class="w-px h-8 bg-gray-800"></div>
                    <div class="text-center">
                        <p class="font-mono font-bold text-white">0</p>
                        <p class="text-xs text-gray-500">Meme Creati</p>
                    </div>
                    <div class="w-px h-8 bg-gray-800"></div>
                    <div class="text-center">
                        <p class="font-mono font-bold text-emerald-500">--</p>
                        <p class="text-xs text-gray-500">Rank</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Menu Items --}}
    <div class="space-y-2">
        {{-- Account Settings --}}
        <a href="#" class="flex items-center justify-between bg-gray-900 border border-gray-800 rounded-xl p-4 hover:bg-gray-800/50 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-white">Impostazioni Account</p>
                    <p class="text-sm text-gray-500">Modifica profilo, email, password</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Notifications --}}
        <a href="#" class="flex items-center justify-between bg-gray-900 border border-gray-800 rounded-xl p-4 hover:bg-gray-800/50 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-white">Notifiche</p>
                    <p class="text-sm text-gray-500">Gestisci le tue notifiche</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- History --}}
        <a href="#" class="flex items-center justify-between bg-gray-900 border border-gray-800 rounded-xl p-4 hover:bg-gray-800/50 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-white">Storico Transazioni</p>
                    <p class="text-sm text-gray-500">Vedi tutte le tue transazioni</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        {{-- Help --}}
        <a href="#" class="flex items-center justify-between bg-gray-900 border border-gray-800 rounded-xl p-4 hover:bg-gray-800/50 transition-colors group">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center group-hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-white">Aiuto & FAQ</p>
                    <p class="text-sm text-gray-500">Come funziona AlmaStreet</p>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- Logout Button --}}
    <div class="mt-8">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500/20 text-red-500 font-medium px-6 py-3 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Esci
            </button>
        </form>
    </div>

    {{-- App Version --}}
    <p class="text-center text-gray-600 text-xs mt-6">AlmaStreet v1.0.0</p>

</div>
@endsection
