@extends('layouts.app')

@section('title', 'Classifica')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">
    
    {{-- Header --}}
    <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-white mb-2">🏆 Classifica Trader</h1>
        <p class="text-gray-400 text-sm">{{ $totalTraders }} trader in competizione</p>
    </div>

    {{-- Time Period Filter --}}
    <div class="flex items-center justify-center gap-2 mb-6">
        <a href="{{ route('leaderboard', ['period' => 'today']) }}" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $currentPeriod === 'today' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            Oggi
        </a>
        <a href="{{ route('leaderboard', ['period' => 'week']) }}" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $currentPeriod === 'week' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            Settimana
        </a>
        <a href="{{ route('leaderboard', ['period' => 'month']) }}" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $currentPeriod === 'month' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            Mese
        </a>
        <a href="{{ route('leaderboard', ['period' => 'all']) }}" 
           class="px-4 py-2 rounded-full text-sm font-medium transition-colors {{ $currentPeriod === 'all' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            Sempre
        </a>
    </div>

    {{-- Top 3 Podium --}}
    @if($podium->count() >= 3)
    <div class="grid grid-cols-3 gap-3 mb-6">
        {{-- 2nd Place --}}
        @php $second = $podium->get(1); @endphp
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center mt-4">
            <div class="w-10 h-10 bg-gray-700 rounded-full mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">🥈</span>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-full mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg font-bold text-white">{{ strtoupper(substr($second->username ?? $second->email, 0, 1)) }}</span>
            </div>
            <p class="text-sm font-medium text-white truncate">{{ $second->username ?? 'Trader' }}</p>
            <p class="text-xs text-gray-500 mb-1">{{ number_format($second->net_worth, 0) }} CFU</p>
            <span class="text-xs font-mono {{ $second->gain_percentage >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                {{ $second->gain_percentage >= 0 ? '+' : '' }}{{ number_format($second->gain_percentage, 1) }}%
            </span>
        </div>
        
        {{-- 1st Place --}}
        @php $first = $podium->get(0); @endphp
        <div class="bg-gradient-to-b from-emerald-500/20 to-gray-900 border border-emerald-500/30 rounded-xl p-4 text-center">
            <div class="w-12 h-12 bg-yellow-500/30 rounded-full mx-auto mb-2 flex items-center justify-center">
                <span class="text-2xl">🥇</span>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full mx-auto mb-2 flex items-center justify-center ring-2 ring-emerald-500/50">
                <span class="text-xl font-bold text-white">{{ strtoupper(substr($first->username ?? $first->email, 0, 1)) }}</span>
            </div>
            <p class="text-sm font-semibold text-white truncate">{{ $first->username ?? 'Trader' }}</p>
            <p class="text-sm text-emerald-400 font-mono font-bold">{{ number_format($first->net_worth, 0) }} CFU</p>
            <span class="text-xs font-mono {{ $first->gain_percentage >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                {{ $first->gain_percentage >= 0 ? '+' : '' }}{{ number_format($first->gain_percentage, 1) }}%
            </span>
        </div>
        
        {{-- 3rd Place --}}
        @php $third = $podium->get(2); @endphp
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center mt-4">
            <div class="w-10 h-10 bg-amber-700/30 rounded-full mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg">🥉</span>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-amber-600 to-amber-800 rounded-full mx-auto mb-2 flex items-center justify-center">
                <span class="text-lg font-bold text-white">{{ strtoupper(substr($third->username ?? $third->email, 0, 1)) }}</span>
            </div>
            <p class="text-sm font-medium text-white truncate">{{ $third->username ?? 'Trader' }}</p>
            <p class="text-xs text-gray-500 mb-1">{{ number_format($third->net_worth, 0) }} CFU</p>
            <span class="text-xs font-mono {{ $third->gain_percentage >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                {{ $third->gain_percentage >= 0 ? '+' : '' }}{{ number_format($third->gain_percentage, 1) }}%
            </span>
        </div>
    </div>
    @elseif($podium->count() > 0)
    {{-- Fewer than 3 traders --}}
    <div class="text-center py-8 mb-6">
        <p class="text-gray-500">Il podio sarà disponibile con almeno 3 trader</p>
    </div>
    @endif

    {{-- Rest of Leaderboard --}}
    @if($leaderboard->count() > 0)
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        @foreach($leaderboard as $index => $user)
        @php $rank = $index + 4; @endphp
        <div class="flex items-center gap-4 p-4 border-b border-gray-800/50 last:border-0 {{ $user->id === auth()->id() ? 'bg-emerald-500/5' : '' }}">
            {{-- Rank --}}
            <div class="w-8 text-center">
                <span class="text-gray-500 font-mono font-bold">{{ $rank }}</span>
            </div>
            
            {{-- Avatar --}}
            @php
                $colors = ['bg-pink-500', 'bg-purple-500', 'bg-blue-500', 'bg-cyan-500', 'bg-teal-500', 'bg-orange-500', 'bg-red-500', 'bg-yellow-500'];
                $colorIndex = $user->id % count($colors);
                $avatarColor = $colors[$colorIndex];
            @endphp
            <div class="w-10 h-10 {{ $avatarColor }} rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-bold text-white">{{ strtoupper(substr($user->username ?? $user->email, 0, 1)) }}</span>
            </div>
            
            {{-- Name --}}
            <div class="flex-1 min-w-0">
                <p class="font-medium text-white truncate">
                    {{ $user->username ?? 'Trader #' . $user->id }}
                    @if($user->id === auth()->id())
                        <span class="text-emerald-500 text-xs">(tu)</span>
                    @endif
                </p>
                <p class="text-xs text-gray-500">{{ number_format($user->portfolio_value, 0) }} CFU investiti</p>
            </div>
            
            {{-- Net Worth & Gain --}}
            <div class="text-right">
                <p class="font-mono font-bold text-white">{{ number_format($user->net_worth, 0) }}</p>
                <span class="text-xs font-mono {{ $user->gain_percentage >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $user->gain_percentage >= 0 ? '+' : '' }}{{ number_format($user->gain_percentage, 1) }}%
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Your Position (if not in top 20) --}}
    @if($currentUserRank && $currentUserRank > 20 && $currentUserData)
    <div class="mt-6 bg-emerald-500/10 border border-emerald-500/30 rounded-xl p-4">
        <div class="flex items-center gap-4">
            <div class="w-8 text-center">
                <span class="text-emerald-500 font-mono font-bold">{{ $currentUserRank }}</span>
            </div>
            <div class="w-10 h-10 bg-emerald-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-bold text-emerald-500">{{ strtoupper(substr(auth()->user()->username ?? auth()->user()->email, 0, 1)) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-medium text-emerald-500">La tua posizione</p>
                <p class="text-xs text-gray-500">{{ number_format($currentUserData->portfolio_value, 0) }} CFU investiti</p>
            </div>
            <div class="text-right">
                <p class="font-mono font-bold text-white">{{ number_format($currentUserData->net_worth, 0) }}</p>
                <span class="text-xs font-mono {{ $currentUserData->gain_percentage >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $currentUserData->gain_percentage >= 0 ? '+' : '' }}{{ number_format($currentUserData->gain_percentage, 1) }}%
                </span>
            </div>
        </div>
    </div>
    @elseif($currentUserRank === null)
    {{-- User not in leaderboard --}}
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
    @endif

    {{-- Empty State --}}
    @if($podium->count() === 0)
    <div class="text-center py-12">
        <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="text-4xl">🏆</span>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Nessun trader ancora</h3>
        <p class="text-gray-500 text-sm">Sii il primo a entrare in classifica!</p>
    </div>
    @endif

</div>
@endsection
