@props(['communications' => collect()])

{{-- Overlay --}}
<div id="notification-overlay" 
     class="fixed inset-0 bg-black/50 z-[60] opacity-0 pointer-events-none transition-opacity duration-300"
     aria-hidden="true">
</div>

{{-- Slide Panel --}}
<aside id="notification-panel"
       class="fixed top-0 right-0 h-full w-[85%] max-w-[320px] lg:max-w-[360px] bg-surface-100 z-[70] transform translate-x-full transition-transform duration-300 ease-out shadow-2xl flex flex-col invisible"
       role="dialog"
       aria-modal="true"
       aria-labelledby="notification-panel-title"
       aria-hidden="true">
    
    {{-- Header --}}
    <header class="h-16 px-4 flex items-center justify-between border-b border-surface-200 bg-surface-100 shrink-0">
        <div>
            <h2 id="notification-panel-title" class="text-xl font-bold text-text-main">Notifiche</h2>
        </div>
        <button type="button" id="notification-close-btn" aria-label="Chiudi pannello notifiche" class="p-2 text-text-muted hover:text-text-main transition-colors rounded-lg hover:bg-surface-200">
            <span class="material-icons text-2xl">close</span>
        </button>
    </header>

    {{-- Market Communications (Active Events) --}}
    @if($communications->isNotEmpty())
    <section class="border-b border-surface-200 bg-gradient-to-r from-brand/10 to-transparent shrink-0">
        <div class="px-4 py-3">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-icons text-brand text-lg" aria-hidden="true">campaign</span>
                <h3 class="text-sm font-semibold text-text-main">Eventi Attivi</h3>
            </div>
            <div class="space-y-2">
                @foreach($communications as $comm)
                <div class="flex items-start gap-2 p-2 bg-surface-100/80 rounded-lg border border-brand/20 relative group">
                    <span class="material-icons text-brand-warning text-sm mt-0.5" aria-hidden="true">schedule</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-text-main leading-tight line-clamp-2">{{ $comm->message }}</p>
                        @if($comm->expires_at)
                        <p class="text-[10px] text-text-muted mt-1">
                            Scade: {{ $comm->expires_at->format('d/m/Y H:i') }}
                        </p>
                        @else
                        <p class="text-[10px] text-text-muted mt-1">Nessuna scadenza</p>
                        @endif
                    </div>
                    <button type="button" 
                            class="event-detail-btn p-1 text-text-muted hover:text-brand transition-colors shrink-0"
                            aria-label="Visualizza dettagli evento"
                            data-title="Evento"
                            data-date="{{ $comm->created_at->format('d/m/Y H:i') }}" 
                            data-message="{{ $comm->message }}">
                        <span class="material-icons text-base" aria-hidden="true">info</span>
                    </button>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Mark All as Read Button --}}
    <div class="px-4 py-2 border-b border-surface-200 shrink-0">
        <button type="button" id="mark-all-read-btn" class="text-sm text-brand hover:text-brand-light transition-colors font-medium">
            Segna tutte come lette
        </button>
    </div>

    {{-- Content (loaded via fetch) --}}
    <div id="notification-content" class="overflow-y-auto flex-1 p-4 custom-scrollbar">
        {{-- Loading state --}}
        <div id="notification-loading" class="flex flex-col items-center justify-center h-48">
            <div class="w-8 h-8 border-2 border-brand border-t-transparent rounded-full animate-spin"></div>
            <span class="text-text-muted text-sm mt-3">Caricamento...</span>
        </div>
    </div>
</aside>

{{-- Detail Modal --}}
<div id="notification-detail-modal"
     class="fixed inset-0 z-[80] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-200"
     aria-hidden="true">
    <div class="absolute inset-0 bg-black/60" id="notification-modal-overlay"></div>
    <div class="relative bg-surface-100 rounded-2xl max-w-md w-[90%] max-h-[80vh] overflow-hidden shadow-2xl transform transition-transform duration-200"
         id="notification-modal-content">
        {{-- Modal Header --}}
        <header class="px-4 py-3 border-b border-surface-200 flex items-center justify-between">
            <h3 id="notification-modal-title" class="text-lg font-bold text-text-main truncate pr-4">Dettaglio Notifica</h3>
            <button type="button" 
                    id="notification-modal-close"
                    class="p-1 text-text-muted hover:text-text-main transition-colors"
                    aria-label="Chiudi">
                <span class="material-icons">close</span>
            </button>
        </header>
        {{-- Modal Body --}}
        <div class="p-4 overflow-y-auto max-h-[60vh]">
            <p id="notification-modal-date" class="text-xs text-text-muted mb-3"></p>
            <div id="notification-modal-message" class="text-text-main text-sm leading-relaxed whitespace-pre-wrap"></div>
        </div>
    </div>
</div>
