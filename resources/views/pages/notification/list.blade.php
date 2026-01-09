{{-- Unread Section --}}
@if($unread->isNotEmpty())
<section class="mb-6">
    <h3 class="text-sm font-semibold text-text-muted uppercase tracking-wide mb-3 flex items-center gap-2">
        <span class="w-2 h-2 bg-brand-danger rounded-full"></span>
        Non lette ({{ $unread->count() }})
    </h3>
    <ul class="space-y-3">
        @foreach($unread as $notification)
        <li class="notification-item bg-surface-200 rounded-xl p-3 border-l-4 border-brand" 
             data-id="{{ $notification->id }}"
             data-read="false">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-text-main truncate">{{ $notification->title }}</h4>
                    <p class="text-xs text-text-muted mt-1">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    {{-- Info Button --}}
                    <button type="button" 
                            class="notification-detail-btn p-2 text-text-muted hover:text-brand hover:bg-surface-300 rounded-lg transition-colors"
                            data-id="{{ $notification->id }}"
                            aria-label="Dettagli notifica">
                        <span class="material-icons text-lg" aria-hidden="true">info</span>
                    </button>
                    {{-- Mark as Read Button --}}
                    <button type="button"
                            class="notification-mark-read-btn p-2 text-text-muted hover:text-brand-success hover:bg-surface-300 rounded-lg transition-colors"
                            data-id="{{ $notification->id }}"
                            aria-label="Segna come letta">
                        <span class="material-icons text-lg" aria-hidden="true">check_circle</span>
                    </button>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</section>
@endif

{{-- Read Section --}}
@if($read->isNotEmpty())
<section>
    <h3 class="text-sm font-semibold text-text-muted uppercase tracking-wide mb-3 flex items-center gap-2">
        <span class="w-2 h-2 bg-surface-300 rounded-full"></span>
        Lette ({{ $read->count() }})
    </h3>
    <ul class="space-y-3">
        @foreach($read as $notification)
        <li class="notification-item bg-surface-50 rounded-xl p-3 opacity-70" 
             data-id="{{ $notification->id }}"
             data-read="true">
            <div class="flex items-start justify-between gap-2">
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-medium text-text-muted truncate">{{ $notification->title }}</h4>
                    <p class="text-xs text-text-muted mt-1">{{ $notification->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                    {{-- Info Button --}}
                    <button type="button" 
                            class="notification-detail-btn p-2 text-text-muted hover:text-brand hover:bg-surface-200 rounded-lg transition-colors"
                            data-id="{{ $notification->id }}"
                            aria-label="Dettagli notifica">
                        <span class="material-icons text-lg" aria-hidden="true">info</span>
                    </button>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</section>
@endif

{{-- Empty State --}}
@if($unread->isEmpty() && $read->isEmpty())
<div class="flex flex-col items-center justify-center h-64 text-center">
    <span class="material-icons text-6xl text-surface-300 mb-4" aria-hidden="true">notifications_none</span>
    <p class="text-text-muted">Nessuna notifica</p>
</div>
@endif
