@extends('layouts.app')

@section('title', 'Portafoglio')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">
    
    {{-- Portfolio Value Card --}}
    <div class="bg-gradient-to-br from-gray-900 to-gray-900/50 border border-gray-800 rounded-2xl p-6 mb-6">
        <p class="text-gray-400 text-sm mb-1">Valore Portafoglio</p>
        <div class="flex items-baseline gap-2 mb-4">
            <span class="font-mono text-3xl font-bold text-white">{{ number_format(auth()->user()->cfu_balance, 2) }}</span>
            <span class="text-emerald-500 font-bold">CFU</span>
        </div>
        
        {{-- Performance --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-1 bg-emerald-500/10 px-3 py-1 rounded-full">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                <span class="text-emerald-500 font-mono font-medium text-sm">+0.00%</span>
            </div>
            <span class="text-gray-500 text-sm">da ieri</span>
        </div>
    </div>

    {{-- Holdings Section --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Le tue posizioni</h2>
            <span class="text-gray-500 text-sm">0 meme</span>
        </div>

        {{-- Empty State --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center">
            <div class="w-16 h-16 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Nessuna posizione</h3>
            <p class="text-gray-500 text-sm mb-4">Inizia a fare trading per vedere le tue posizioni qui</p>
            <a href="{{ route('marketplace') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-gray-900 font-semibold px-6 py-3 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Vai al Market
            </a>
        </div>

        {{-- Skeleton Holdings (shown when loading) --}}
        <div class="hidden space-y-3">
            @for($i = 0; $i < 3; $i++)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 animate-pulse">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-800 rounded-lg flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <div class="h-4 w-20 bg-gray-800 rounded mb-2"></div>
                        <div class="h-3 w-32 bg-gray-800 rounded"></div>
                    </div>
                    <div class="text-right">
                        <div class="h-4 w-16 bg-gray-800 rounded mb-1 ml-auto"></div>
                        <div class="h-3 w-12 bg-gray-800 rounded ml-auto"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Transazioni recenti</h2>
            <a href="#" class="text-emerald-500 text-sm font-medium hover:text-emerald-400 transition-colors">Vedi tutte</a>
        </div>

        {{-- Empty State --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
            <div class="w-12 h-12 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <p class="text-gray-500 text-sm">Nessuna transazione</p>
        </div>

        {{-- Skeleton Transactions (shown when loading) --}}
        <div class="hidden bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            @for($i = 0; $i < 3; $i++)
            <div class="flex items-center gap-4 p-4 border-b border-gray-800/50 last:border-0 animate-pulse">
                <div class="w-10 h-10 bg-gray-800 rounded-lg flex-shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <div class="h-4 w-24 bg-gray-800 rounded mb-1"></div>
                    <div class="h-3 w-16 bg-gray-800 rounded"></div>
                </div>
                <div class="text-right">
                    <div class="h-4 w-14 bg-gray-800 rounded mb-1 ml-auto"></div>
                    <div class="h-3 w-10 bg-gray-800 rounded ml-auto"></div>
                </div>
            </div>
            @endfor
        </div>
    </div>

</div>
@endsection
