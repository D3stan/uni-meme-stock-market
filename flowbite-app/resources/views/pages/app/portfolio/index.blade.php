@extends('layouts.app')

@section('title', 'Portafoglio')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">
    
    {{-- Hero Section - Valore Totale --}}
    <div class="bg-gradient-to-br from-gray-900 to-gray-900/50 border border-gray-800 rounded-2xl p-6 mb-6">
        <p class="text-gray-400 text-sm text-center mb-2">Valore Totale</p>
        <div class="flex items-baseline justify-center gap-2 mb-4">
            <span class="font-mono text-4xl font-bold text-white">{{ number_format($totalValue, 0, ',', '.') }}</span>
            <span class="text-emerald-500 font-bold text-xl">CFU</span>
        </div>
        
        {{-- Performance Badge --}}
        <div class="flex justify-center">
            @if($totalGainPercent >= 0)
                <div class="flex items-center gap-1 bg-emerald-500/10 px-4 py-1.5 rounded-full">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                    <span class="text-emerald-500 font-mono font-medium text-sm">+{{ number_format($totalGainPercent, 1) }}% (24h)</span>
                </div>
            @else
                <div class="flex items-center gap-1 bg-red-500/10 px-4 py-1.5 rounded-full">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6"/>
                    </svg>
                    <span class="text-red-500 font-mono font-medium text-sm">{{ number_format($totalGainPercent, 1) }}% (24h)</span>
                </div>
            @endif
        </div>
    </div>

    {{-- Asset Allocation Card --}}
    <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 mb-6">
        <div class="flex items-center gap-6">
            {{-- Donut Chart --}}
            <div class="relative w-24 h-24 flex-shrink-0">
                <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 100 100">
                    {{-- Background circle --}}
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#374151" stroke-width="12"/>
                    {{-- Invested portion (green) --}}
                    @if($investedPercent > 0)
                    <circle cx="50" cy="50" r="40" fill="none" stroke="#10b981" stroke-width="12"
                        stroke-dasharray="{{ $investedPercent * 2.51327 }} {{ (100 - $investedPercent) * 2.51327 }}"
                        stroke-linecap="round"/>
                    @endif
                </svg>
                {{-- Center text --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-[10px] text-gray-400 uppercase tracking-wide">Investito</span>
                    <span class="text-white font-bold text-lg">{{ $investedPercent }}%</span>
                </div>
            </div>
            
            {{-- Legend --}}
            <div class="flex-1 space-y-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
                        <span class="text-gray-400 text-sm">Investito</span>
                    </div>
                    <span class="text-white font-mono font-semibold">{{ number_format($investedValue, 0, ',', '.') }} CFU</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-gray-600 rounded-full"></span>
                        <span class="text-gray-400 text-sm">Liquidità</span>
                    </div>
                    <span class="text-white font-mono font-semibold">{{ number_format($liquidBalance, 0, ',', '.') }} CFU</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Holdings Section --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">I Tuoi Meme</h2>
            @if(count($holdings) > 0)
                <a href="#" class="text-emerald-500 text-sm font-semibold hover:text-emerald-400 transition-colors uppercase tracking-wide">Vedi Tutti</a>
            @endif
        </div>

        @if(count($holdings) > 0)
            {{-- Holdings List --}}
            <div class="space-y-3">
                @foreach($holdings as $holding)
                <a href="{{ route('meme.show', $holding['meme_id']) }}" class="block bg-gray-900 border border-gray-800 rounded-xl p-4 hover:border-gray-700 transition-colors">
                    <div class="flex items-center gap-4">
                        {{-- Meme Image --}}
                        <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-800">
                            @if($holding['image_path'])
                                <img src="{{ asset('storage/memes/' . $holding['image_path']) }}" alt="{{ $holding['title'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Meme Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-white font-bold">{{ $holding['ticker'] }}</span>
                            </div>
                            <p class="text-gray-500 text-xs truncate uppercase">{{ $holding['title'] }}</p>
                        </div>
                        
                        {{-- Quantity & Value --}}
                        <div class="text-right">
                            <div class="flex items-baseline gap-1">
                                <span class="text-white font-mono font-semibold">
                                    @if($holding['quantity'] >= 1000)
                                        {{ number_format($holding['quantity'] / 1000, 1) }}k
                                    @else
                                        {{ $holding['quantity'] }}
                                    @endif
                                </span>
                                <span class="text-gray-500 text-xs">pz</span>
                            </div>
                            <p class="text-gray-500 text-xs font-mono">≈{{ number_format($holding['current_value'], 0) }} CFU</p>
                        </div>
                        
                        {{-- Change Badge --}}
                        <div class="text-right min-w-[70px]">
                            @if($holding['change_24h'] > 0)
                                <span class="inline-block bg-emerald-500/10 text-emerald-500 text-xs font-mono font-semibold px-2 py-0.5 rounded">+{{ number_format($holding['change_24h'], 1) }}%</span>
                                <p class="text-emerald-500 text-xs font-mono mt-0.5">+{{ number_format($holding['change_24h_value'], 0) }} CFU</p>
                            @elseif($holding['change_24h'] < 0)
                                <span class="inline-block bg-red-500/10 text-red-500 text-xs font-mono font-semibold px-2 py-0.5 rounded">{{ number_format($holding['change_24h'], 1) }}%</span>
                                <p class="text-red-500 text-xs font-mono mt-0.5">{{ number_format($holding['change_24h_value'], 0) }} CFU</p>
                            @else
                                <span class="inline-block bg-gray-700/50 text-gray-400 text-xs font-mono font-semibold px-2 py-0.5 rounded">0.0%</span>
                                <p class="text-gray-500 text-xs font-mono mt-0.5">-</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @else
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
        @endif
    </div>

    {{-- Recent Transactions --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-white">Transazioni Recenti</h2>
            @if(count($recentTransactions) > 0)
                <a href="#" class="text-emerald-500 text-sm font-semibold hover:text-emerald-400 transition-colors uppercase tracking-wide">Vedi Tutte</a>
            @endif
        </div>

        @if(count($recentTransactions) > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                @foreach($recentTransactions as $transaction)
                <div class="flex items-center gap-4 p-4 border-b border-gray-800/50 last:border-0">
                    {{-- Transaction Type Icon --}}
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                        @if($transaction['type'] === 'buy') bg-emerald-500/10
                        @elseif($transaction['type'] === 'sell') bg-red-500/10
                        @elseif($transaction['type'] === 'dividend') bg-yellow-500/10
                        @else bg-gray-800
                        @endif">
                        @if($transaction['type'] === 'buy')
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        @elseif($transaction['type'] === 'sell')
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                            </svg>
                        @elseif($transaction['type'] === 'dividend')
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        @endif
                    </div>
                    
                    {{-- Transaction Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-medium">
                            @if($transaction['type'] === 'buy')
                                Acquisto {{ $transaction['meme_ticker'] ?? '' }}
                            @elseif($transaction['type'] === 'sell')
                                Vendita {{ $transaction['meme_ticker'] ?? '' }}
                            @elseif($transaction['type'] === 'dividend')
                                Dividendo {{ $transaction['meme_ticker'] ?? '' }}
                            @elseif($transaction['type'] === 'bonus')
                                Bonus Benvenuto
                            @elseif($transaction['type'] === 'listing_fee')
                                Fee Listing
                            @else
                                {{ ucfirst($transaction['type']) }}
                            @endif
                        </p>
                        <p class="text-gray-500 text-xs">
                            @if($transaction['quantity'])
                                {{ $transaction['quantity'] }} pz • 
                            @endif
                            {{ $transaction['executed_at']->diffForHumans() }}
                        </p>
                    </div>
                    
                    {{-- Amount --}}
                    <div class="text-right">
                        @if(in_array($transaction['type'], ['sell', 'dividend', 'bonus']))
                            <span class="text-emerald-500 font-mono font-semibold">+{{ number_format($transaction['total_amount'], 2) }}</span>
                        @else
                            <span class="text-red-500 font-mono font-semibold">-{{ number_format($transaction['total_amount'], 2) }}</span>
                        @endif
                        <span class="text-gray-500 text-xs ml-1">CFU</span>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
                <div class="w-12 h-12 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm">Nessuna transazione</p>
            </div>
        @endif
    </div>
</div>
@endsection
