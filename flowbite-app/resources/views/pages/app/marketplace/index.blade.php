@extends('layouts.app')

@section('title', 'Market')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-4">
    
    {{-- Search Bar --}}
    <div class="mb-4">
        <form action="{{ route('marketplace') }}" method="GET" class="relative">
            <input type="text" 
                   name="search"
                   value="{{ $searchQuery ?? '' }}"
                   placeholder="Cerca meme..." 
                   class="w-full bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 pl-11 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 transition-colors">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            @if($searchQuery ?? false)
                <a href="{{ route('marketplace') }}" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
            @endif
        </form>
    </div>

    {{-- Filter Pills --}}
    <div class="flex items-center gap-2 mb-6 overflow-x-auto pb-2 scrollbar-hide">
        <a href="{{ route('marketplace', ['filter' => 'all']) }}" 
           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors {{ ($currentFilter ?? 'all') === 'all' ? 'bg-white text-gray-900' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            Tutti
        </a>
        <a href="{{ route('marketplace', ['filter' => 'top-gainers']) }}" 
           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors {{ ($currentFilter ?? '') === 'top-gainers' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <span>🚀</span>
            Top Gainer
        </a>
        <a href="{{ route('marketplace', ['filter' => 'new']) }}" 
           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors {{ ($currentFilter ?? '') === 'new' ? 'bg-purple-500/20 text-purple-500' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <span>✨</span>
            New
        </a>
        <a href="{{ route('marketplace', ['filter' => 'high-risk']) }}" 
           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors {{ ($currentFilter ?? '') === 'high-risk' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-gray-800/50 text-gray-400 hover:bg-gray-800 hover:text-white' }}">
            <span>⚠️</span>
            High Risk
        </a>
    </div>

    {{-- Meme Feed --}}
    <div class="space-y-4" id="meme-feed">
        @forelse($memes as $meme)
            <x-meme.card :meme="$meme" />
        @empty
            {{-- Empty State --}}
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">Nessun meme trovato</h3>
                <p class="text-gray-500 text-sm mb-4">
                    @if($searchQuery ?? false)
                        Prova a cercare con altri termini
                    @else
                        Il mercato è vuoto. Sii il primo a creare un meme!
                    @endif
                </p>
                <a href="{{ route('meme.create') }}" class="inline-flex items-center gap-2 bg-emerald-500 hover:bg-emerald-400 text-gray-900 font-semibold px-6 py-3 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Crea Meme
                </a>
            </div>
        @endforelse
    </div>

    {{-- Load More / Pagination --}}
    @if($memes->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $memes->appends(['filter' => $currentFilter])->links() }}
        </div>
    @endif

    {{-- Skeleton Loading (hidden, shown via JS when loading more) --}}
    <div id="loading-skeletons" class="hidden space-y-4 mt-4">
        @for($i = 0; $i < 3; $i++)
            <x-meme.card-skeleton />
        @endfor
    </div>

</div>
@endsection
