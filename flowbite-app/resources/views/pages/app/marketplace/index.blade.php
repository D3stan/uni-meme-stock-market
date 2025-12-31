@extends('layouts.app')

@section('title', 'Market')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">
    
    {{-- Search Bar --}}
    <div class="mb-6">
        <div class="relative">
            <input type="text" 
                   placeholder="Cerca meme..." 
                   class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 pl-11 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 transition-colors">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    {{-- Filter Pills --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
        <button class="flex items-center gap-2 px-4 py-2 bg-emerald-500/20 text-emerald-500 rounded-full text-sm font-medium whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
            Trending
        </button>
        <button class="px-4 py-2 bg-gray-800/50 text-gray-400 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-800 hover:text-white transition-colors">
            Nuovi
        </button>
        <button class="px-4 py-2 bg-gray-800/50 text-gray-400 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-800 hover:text-white transition-colors">
            Top Gainers
        </button>
        <button class="px-4 py-2 bg-gray-800/50 text-gray-400 rounded-full text-sm font-medium whitespace-nowrap hover:bg-gray-800 hover:text-white transition-colors">
            Top Losers
        </button>
    </div>

    {{-- Skeleton Loading Cards --}}
    <div class="space-y-3">
        @for($i = 0; $i < 6; $i++)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 animate-pulse">
            <div class="flex items-center gap-4">
                {{-- Skeleton Image --}}
                <div class="w-14 h-14 bg-gray-800 rounded-lg flex-shrink-0"></div>
                
                {{-- Skeleton Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="h-4 w-24 bg-gray-800 rounded"></div>
                        <div class="h-4 w-12 bg-gray-800 rounded"></div>
                    </div>
                    <div class="h-3 w-16 bg-gray-800 rounded"></div>
                </div>
                
                {{-- Skeleton Price --}}
                <div class="text-right flex-shrink-0">
                    <div class="h-5 w-16 bg-gray-800 rounded mb-1 ml-auto"></div>
                    <div class="h-4 w-12 bg-gray-800 rounded ml-auto"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>

    {{-- Empty State (hidden by default, shown when no results) --}}
    <div class="hidden text-center py-12">
        <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Nessun meme trovato</h3>
        <p class="text-gray-500 text-sm">Prova a modificare i filtri di ricerca</p>
    </div>

</div>
@endsection
