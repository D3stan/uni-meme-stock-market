@props(['active' => null ])

{{-- Mobile Top Bar --}}
<div class="lg:hidden fixed top-0 left-0 w-full z-50 bg-[#11271a] px-4 py-2 flex items-center justify-between rounded-b-2xl shadow-lg">
    <img src="{{ asset('favicon.ico') }}" alt="Favicon" class="w-7 h-7">
    <span class="font-mono font-bold text-white">
        1,250.00 <span class="text-green-400">CFU</span>
    </span>
    <div class="relative">
        <span class="material-icons text-white text-2xl">notifications</span>
        <span class="absolute top-0 right-0 block w-2 h-2 bg-red-500 rounded-full ring-2 ring-[#11271a]"></span>
    </div>
</div>

{{-- Mobile Bottom Navigation --}}
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-[#1a2e23]/95 backdrop-blur-lg border-t border-gray-800/50">
    <div class="h-full flex items-center justify-center gap-4 px-6 py-3 max-w-2xl mx-auto">
        {{-- Market --}}
        <a href="{{ route('market') }}" class="flex items-center justify-center {{ $active === 'market' ? 'bg-green-600 px-4' : '' }} rounded-full h-12 min-w-[48px] group transition-colors">
            <span class="material-icons {{ $active === 'market' ? 'text-[#0f2216] text-2xl' : 'text-gray-400 text-2xl' }} group-hover:text-green-500 transition-colors">
                dashboard
            </span>
        </a>

        {{-- Leaderboard --}}
        <a href="{{ route('leaderboard') }}" class="flex items-center justify-center {{ $active === 'trending' ? 'bg-green-600 px-4' : '' }} rounded-full h-12 min-w-[48px] group transition-colors">
            <span class="material-icons {{ $active === 'trending' ? 'text-[#0f2216] text-2xl' : 'text-gray-400 text-2xl' }} group-hover:text-green-500 transition-colors">
                emoji_events
            </span>
        </a>

        {{-- Central Create Button --}}
        <a href="{{ route('create') }}" class="flex items-center justify-center bg-green-500 rounded-full border-4 border-[#0f2216] h-16 w-16 group transition-colors hover:bg-green-600 active:scale-95">
            <span class="material-icons text-white text-3xl">
                add
            </span>
        </a>

        {{-- Portfolio --}}
        <a href="{{ route('portfolio') }}" class="flex items-center justify-center {{ $active === 'leaderboard' ? 'bg-green-600 px-4' : '' }} rounded-full h-12 min-w-[48px] group transition-colors">
            <span class="material-icons {{ $active === 'leaderboard' ? 'text-[#0f2216] text-2xl' : 'text-gray-400 text-2xl' }} group-hover:text-green-500 transition-colors">
                account_balance_wallet
            </span>
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile') }}" class="flex items-center justify-center {{ $active === 'profile' ? 'bg-green-600 px-4' : '' }} rounded-full h-12 min-w-[48px] group transition-colors">
            <span class="material-icons {{ $active === 'profile' ? 'text-[#0f2216] text-2xl' : 'text-gray-400 text-2xl' }} group-hover:text-green-500 transition-colors">
                person
            </span>
        </a>
    </div>
</nav>

{{-- Desktop Top Navigation --}}
<nav class="hidden lg:block fixed top-0 left-0 right-0 z-50 h-18 bg-[#1a2e23] border-b border-gray-700">
    <div class="max-w-7xl mx-auto px-6 h-full flex items-center justify-between">
        {{-- Logo --}}
        <div class="flex items-center">
            <img src="{{ asset('favicon.ico') }}" alt="Favicon" class="w-6 h-6 mr-2">
            <span class="text-2xl font-black text-green-500">AlmaStreet</span>
        </div>

        {{-- Navigation Items --}}
        <div class="flex items-center gap-8">
            <a href="{{ route('market') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'market' ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <span class="material-icons text-xl">trending_up</span>
                <span class="font-medium">Market</span>
            </a>

            <a href="{{ route('leaderboard') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'leaderboard' ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <span class="material-icons text-xl">emoji_events</span>
                <span class="font-medium">Classifica</span>
            </a>

            <a href="{{ route('create') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'create' ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <span class="material-icons text-xl">add</span>
                <span class="font-medium">Crea Meme</span>
            </a>

            <a href="{{ route('portfolio') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'leaderboard' ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <span class="material-icons text-xl">account_balance_wallet</span>
                <span class="font-medium">Portafoglio</span>
            </a>

            <a href="{{ route('profile') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg {{ $active === 'profile' ? 'bg-green-600 text-white' : 'text-gray-300 hover:bg-gray-800' }} transition-colors">
                <span class="material-icons text-xl">person</span>
                <span class="font-medium">Profilo</span>
            </a>
        </div>

        {{-- User Balance & Notifications --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-4 py-2 bg-gray-800 rounded-lg">
                <span class="font-mono font-bold text-white">1,250.00 CFU</span>
            </div>
            
            <button class="relative p-2 text-gray-300 hover:text-white transition-colors">
                <span class="material-icons">notifications</span>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>
        </div>
    </div>
</nav>
