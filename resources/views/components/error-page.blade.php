@props(['code', 'title', 'message'])

<x-base :title="$title">
    <main class="min-h-screen flex items-center justify-center p-4 bg-surface-50 relative overflow-hidden">
        {{-- Background Elements: Decorative, hidden from assistive technology --}}
        <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-brand rounded-full blur-3xl opacity-5"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-brand-accent rounded-full blur-3xl opacity-5"></div>
        </div>

        <div class="card-base max-w-lg w-full p-8 text-center space-y-6 relative z-10 border-surface-200/50 backdrop-blur-sm">
            {{-- Error Code --}}
            <div class="relative">
                <h1 class="text-9xl font-bold text-brand font-mono tracking-tighter opacity-90 select-none">
                    {{ $code }}
                </h1>
            </div>
            
            <div class="space-y-4">
                <h2 class="text-2xl font-bold text-text-main">{{ $title }}</h2>
                <p class="text-text-muted text-lg leading-relaxed">{{ $message }}</p>
            </div>

            <div class="pt-6">
                <a href="{{ route('welcome') }}" class="btn-primary inline-flex items-center gap-2 group transition-all duration-300 hover:scale-105">
                    <span class="material-icons text-sm group-hover:-translate-x-1 transition-transform" aria-hidden="true">arrow_back</span>
                    Torna alla Home
                </a>
            </div>
        </div>
    </main>
</x-base>