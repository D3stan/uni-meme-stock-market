@props(['active' => null, 'balance' => null ])

{{-- Mobile Top Bar --}}
<div class="lg:hidden fixed top-0 left-0 w-full z-50 bg-surface-100 px-4 py-2 flex items-center justify-between shadow-lg">
    <img src="{{ asset('favicon.ico') }}" alt="Favicon" class="w-7 h-7">
    <span class="font-mono font-bold text-text-main">
        {{ $balance ? number_format($balance, 2) : '0.00' }} <span class="text-brand">CFU</span>
    </span>
    <div class="relative">
        <a href="#" aria-label="Notifiche">
            <span aria-hidden="true" class="material-icons text-text-main text-2xl">notifications</span>
            <span class="absolute top-0 right-0 block w-2 h-2 bg-brand-danger rounded-full ring-2 ring-surface-100"></span>
        </a>
    </div>
</div>

{{-- Mobile Bottom Navigation --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-surface-100/95 backdrop-blur-lg border-t border-surface-200/50">
    <div class="h-full flex items-center justify-center gap-4 px-6 py-3 max-w-2xl mx-auto">
        {{-- Market --}}
        <a href="{{ route('market') }}" aria-label="Marketplace" class="flex items-center justify-center {{ $active === 'market' ? 'bg-brand px-4' : '' }} rounded-full h-12 min-w-[48px] group transition-colors">
            <span aria-hidden="true" class="material-icons {{ $active === 'market' ? 'text-surface-50 text-2xl' : 'text-text-muted text-2xl' }} group-hover:text-brand transition-colors">
                dashboard
            </span>
        </a>

        {{-- Leaderboard --}}
        <a href="{{ route('leaderboard') }}" aria-label="Classifica" class="flex items-center justify-center {{ $active === 'leaderboard' ? 'bg-brand px-4' : '' }} rounded-full h-12 min-w-[48px] group transition-colors">
            <span aria-hidden="true" class="material-icons {{ $active === 'leaderboard' ? 'text-surface-50 text-2xl' : 'text-text-muted text-2xl' }} group-hover:text-brand transition-colors">
                emoji_events
            </span>
        </a>

        {{-- Central Create Button --}}
        <a href="{{ route('create') }}" aria-label="Crea" class="flex items-center justify-center bg-brand rounded-full border-4 border-surface-50 h-16 w-16 group transition-colors hover:bg-brand-light active:scale-95">
            <span aria-hidden="true" class="material-icons text-text-main text-3xl">
                add
            </span>
        </a>

        {{-- Portfolio --}}
        <a href="{{ route('portfolio') }}" aria-label="Porfolio" class="flex items-center justify-center {{ $active === 'portfolio' ? 'bg-brand px-4' : '' }} rounded-full h-12 min-w-[48px] group transition-colors">
            <span aria-hidden="true" class="material-icons {{ $active === 'portfolio' ? 'text-surface-50 text-2xl' : 'text-text-muted text-2xl' }} group-hover:text-brand transition-colors">
                account_balance_wallet
            </span>
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile') }}" aria-label="Profilo" class="flex items-center justify-center {{ $active === 'profile' ? 'bg-brand px-4' : '' }} rounded-full h-12 min-w-[48px] group transition-colors">
            <span aria-hidden="true" class="material-icons {{ $active === 'profile' ? 'text-surface-50 text-2xl' : 'text-text-muted text-2xl' }} group-hover:text-brand transition-colors">
                person
            </span>
        </a>
    </div>
</nav>

{{-- Desktop Top Navigation --}}
<nav class="hidden lg:block fixed top-0 left-0 right-0 z-50 h-18 bg-surface-100 border-b border-surface-200">
    <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">
        {{-- Logo --}}
        <div class="flex items-center">
            <img src="{{ asset('favicon.ico') }}" alt="Favicon" class="w-6 h-6 mr-2">
            <span class="text-2xl font-black text-brand">AlmaStreet</span>
        </div>

        {{-- Navigation Items --}}
        <div class="flex items-center gap-8">
            <a href="{{ route('market') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'market' ? 'bg-brand text-text-main' : 'text-text-muted hover:bg-surface-200' }} transition-colors">
                <span aria-hidden="true" class="material-icons text-xl">trending_up</span>
                <span class="font-medium">Market</span>
            </a>

            <a href="{{ route('leaderboard') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'leaderboard' ? 'bg-brand text-text-main' : 'text-text-muted hover:bg-surface-200' }} transition-colors">
                <span aria-hidden="true" class="material-icons text-xl">emoji_events</span>
                <span class="font-medium">Classifica</span>
            </a>

            <a href="{{ route('create') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'create' ? 'bg-brand text-text-main' : 'text-text-muted hover:bg-surface-200' }} transition-colors">
                <span aria-hidden="true" class="material-icons text-xl">add</span>
                <span class="font-medium">Crea Meme</span>
            </a>

            <a href="{{ route('portfolio') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'portfolio' ? 'bg-brand text-text-main' : 'text-text-muted hover:bg-surface-200' }} transition-colors">
                <span aria-hidden="true" class="material-icons text-xl">account_balance_wallet</span>
                <span class="font-medium">Portafoglio</span>
            </a>

            <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'profile' ? 'bg-brand text-text-main' : 'text-text-muted hover:bg-surface-200' }} transition-colors">
                <span aria-hidden="true" class="material-icons text-xl">person</span>
                <span class="font-medium">Profilo</span>
            </a>
        </div>

        {{-- User Balance & Notifications --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 bg-surface-200 rounded-lg border-2 border-brand/50">
                <span class="font-mono font-bold text-text-main">{{ $balance ? number_format($balance, 2) : '1,250.00' }} CFU</span>
            </div>
            
            <a href="#" aria-label="Notifiche" class="relative p-2 text-text-muted hover:text-text-main transition-colors">
                <span aria-hidden="true" class="material-icons">notifications</span>
                <span class="absolute top-1 right-1 w-2 h-2 bg-brand-danger rounded-full"></span>
            </a>
        </div>
    </div>
</nav>
