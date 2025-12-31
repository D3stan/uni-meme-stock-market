@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-8">
    {{-- Dark Overlay Background --}}
    <div class="fixed inset-0 bg-gray-950/90"></div>

    {{-- Animated Confetti --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="confetti" style="left: 5%; background: #10b981; animation-delay: 0s;"></div>
        <div class="confetti" style="left: 10%; background: #fbbf24; animation-delay: 0.3s;"></div>
        <div class="confetti" style="left: 15%; background: #10b981; animation-delay: 0.6s;"></div>
        <div class="confetti" style="left: 25%; background: #f472b6; animation-delay: 0.2s;"></div>
        <div class="confetti" style="left: 35%; background: #10b981; animation-delay: 0.8s;"></div>
        <div class="confetti" style="left: 45%; background: #60a5fa; animation-delay: 0.4s;"></div>
        <div class="confetti" style="left: 55%; background: #fbbf24; animation-delay: 0.1s;"></div>
        <div class="confetti" style="left: 65%; background: #10b981; animation-delay: 0.7s;"></div>
        <div class="confetti" style="left: 75%; background: #f472b6; animation-delay: 0.5s;"></div>
        <div class="confetti" style="left: 85%; background: #60a5fa; animation-delay: 0.9s;"></div>
        <div class="confetti" style="left: 90%; background: #10b981; animation-delay: 0.35s;"></div>
        <div class="confetti" style="left: 95%; background: #fbbf24; animation-delay: 0.65s;"></div>
    </div>

    {{-- Modal Card --}}
    <div class="relative z-10 w-full max-w-sm">
        <div class="bg-gradient-to-b from-gray-800/90 to-gray-900/95 border border-gray-700/50 rounded-3xl p-8 text-center shadow-2xl backdrop-blur-sm">
            
            {{-- Party Popper Emoji --}}
            <div class="mb-6">
                <span class="text-7xl">🎉</span>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">
                Benvenuto su
            </h1>
            <h2 class="text-2xl sm:text-3xl font-bold text-emerald-500 mb-4">
                AlmaStreet!
            </h2>

            {{-- Subtitle --}}
            <p class="text-gray-400 text-base mb-6">
                Ecco il tuo bonus di benvenuto:
            </p>

            {{-- Bonus Amount Card --}}
            <div class="mb-6">
                <div class="w-full py-5 bg-emerald-500/10 border-2 border-emerald-500/30 rounded-2xl">
                    <span class="text-5xl font-black text-emerald-500 font-mono tracking-tight">
                        {{ auth()->user()?->cfu_balance ?? 100 }} CFU
                    </span>
                </div>
            </div>

            {{-- Description --}}
            <p class="text-gray-400 text-sm leading-relaxed mb-8">
                Usa questi CFU per iniziare a fare trading sui meme più caldi dell'ateneo!
            </p>

            {{-- CTA Button --}}
            <a 
                href="{{ route('home') }}"
                class="flex items-center justify-center gap-3 w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-gray-900 font-bold rounded-full text-lg transition-all duration-200 transform hover:scale-[1.02]"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Vai al Mercato
            </a>
        </div>
    </div>
</div>

<style>
    @keyframes confetti-fall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0.8;
        }
    }

    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        top: -20px;
        animation: confetti-fall 4s ease-in-out infinite;
    }

    .confetti:nth-child(odd) {
        width: 8px;
        height: 14px;
        border-radius: 2px;
    }

    .confetti:nth-child(even) {
        width: 12px;
        height: 8px;
        border-radius: 50%;
    }
</style>
@endsection
