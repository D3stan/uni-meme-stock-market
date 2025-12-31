@extends('layouts.app')

@section('title', 'Classifica')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">
    
    {{-- Header --}}
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">🏆 Classifica Trader</h1>
        <p class="text-gray-400 text-sm">I migliori trader del mercato</p>
    </div>

    {{-- Time Period Filter --}}
    <div class="flex items-center justify-center gap-2 mb-6">
        <button class="px-4 py-2 bg-emerald-500/20 text-emerald-500 rounded-full text-sm font-medium">
            Oggi
        </button>
        <button class="px-4 py-2 bg-gray-800/50 text-gray-400 rounded-full text-sm font-medium hover:bg-gray-800 hover:text-white transition-colors">
            Settimana
        </button>
        <button class="px-4 py-2 bg-gray-800/50 text-gray-400 rounded-full text-sm font-medium hover:bg-gray-800 hover:text-white transition-colors">
            Mese
        </button>
        <button class="px-4 py-2 bg-gray-800/50 text-gray-400 rounded-full text-sm font-medium hover:bg-gray-800 hover:text-white transition-colors">
            Sempre
        </button>
    </div>

    {{-- Top 3 Podium (Skeleton) --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        {{-- 2nd Place --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center animate-pulse">
            <div class="w-10 h-10 bg-gray-700 rounded-full mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">🥈</span>
            </div>
            <div class="w-12 h-12 bg-gray-800 rounded-full mx-auto mb-2"></div>
            <div class="h-3 w-16 bg-gray-800 rounded mx-auto mb-1"></div>
            <div class="h-4 w-14 bg-gray-800 rounded mx-auto"></div>
        </div>
        
        {{-- 1st Place --}}
        <div class="bg-gradient-to-b from-emerald-500/20 to-gray-900 border border-emerald-500/30 rounded-xl p-4 text-center animate-pulse -mt-4">
            <div class="w-12 h-12 bg-yellow-500/30 rounded-full mx-auto mb-2 flex items-center justify-center">
                <span class="text-2xl">🥇</span>
            </div>
            <div class="w-14 h-14 bg-gray-800 rounded-full mx-auto mb-2 ring-2 ring-emerald-500/50"></div>
            <div class="h-4 w-20 bg-gray-800 rounded mx-auto mb-1"></div>
            <div class="h-5 w-16 bg-gray-800 rounded mx-auto"></div>
        </div>
        
        {{-- 3rd Place --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center animate-pulse">
            <div class="w-10 h-10 bg-amber-700/30 rounded-full mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">🥉</span>
            </div>
            <div class="w-12 h-12 bg-gray-800 rounded-full mx-auto mb-2"></div>
            <div class="h-3 w-16 bg-gray-800 rounded mx-auto mb-1"></div>
            <div class="h-4 w-14 bg-gray-800 rounded mx-auto"></div>
        </div>
    </div>

    {{-- Rest of Leaderboard (Skeleton) --}}
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        @for($i = 4; $i <= 10; $i++)
        <div class="flex items-center gap-4 p-4 border-b border-gray-800/50 last:border-0 animate-pulse">
            {{-- Rank --}}
            <div class="w-8 text-center">
                <span class="text-gray-500 font-mono font-bold">{{ $i }}</span>
            </div>
            
            {{-- Avatar --}}
            <div class="w-10 h-10 bg-gray-800 rounded-full flex-shrink-0"></div>
            
            {{-- Name --}}
            <div class="flex-1 min-w-0">
                <div class="h-4 w-24 bg-gray-800 rounded mb-1"></div>
                <div class="h-3 w-16 bg-gray-800 rounded"></div>
            </div>
            
            {{-- Gain --}}
            <div class="text-right">
                <div class="h-4 w-16 bg-gray-800 rounded"></div>
            </div>
        </div>
        @endfor
    </div>

    {{-- Your Position --}}
    <div class="mt-6 bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <div class="w-8 text-center">
                <span class="text-emerald-500 font-mono font-bold">--</span>
            </div>
            <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-emerald-500">La tua posizione</p>
                <p class="text-xs text-gray-500">Fai trading per entrare in classifica!</p>
            </div>
            <div class="text-right">
                <span class="text-emerald-500 font-mono font-bold">+0.00%</span>
            </div>
        </div>
    </div>

</div>
@endsection
