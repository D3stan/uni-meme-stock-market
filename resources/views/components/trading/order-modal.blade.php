{{-- Bottom Sheet Order Modal (Buy/Sell) --}}
@props(['meme', 'userHoldings'])

{{-- Backdrop --}}
<div id="order-modal-backdrop" class="fixed inset-0 bg-surface-50/60 z-50 hidden transition-opacity opacity-0"></div>

{{-- Bottom Sheet Modal --}}
<div id="order-modal" 
     role="dialog"
     aria-modal="true"
     aria-labelledby="modal-title"
     class="fixed bottom-0 left-0 right-0 bg-surface-100 rounded-t-3xl z-[51] hidden
            transform translate-y-full transition-transform duration-300 ease-out
            max-h-[85vh] overflow-y-auto">
    
    {{-- Handle Bar --}}
    <div class="flex justify-center pt-3 pb-2">
        <div class="w-10 h-1 bg-surface-200 rounded-full"></div>
    </div>

    <div class="px-6 pb-8">
        {{-- Header --}}
        <div class="mb-6">
            <h2 id="modal-title" class="text-2xl font-bold mb-2">
                Acquista {{ $meme->ticker }}
            </h2>
            <div class="flex items-center gap-2 bg-surface-200/50 rounded-lg px-3 py-2 w-fit">
                <span class="material-icons text-sm text-text-muted" aria-hidden="true">account_balance_wallet</span>
                <span class="text-sm text-text-muted">Saldo:</span>
                <span id="user-balance" class="font-mono font-semibold">
                    {{ number_format(auth()->user()->cfu_balance, 2) }} CFU
                </span>
            </div>
            
            {{-- Holdings info (for sell modal) --}}
            <div id="holdings-info" class="hidden mt-2 bg-brand-accent/10 border border-brand-accent/30 rounded-lg px-3 py-2">
                <span class="text-sm text-brand-accent">
                    Possiedi: <span id="user-holdings-quantity" class="font-mono font-semibold">0</span> azioni
                </span>
            </div>
        </div>

        {{-- Quantity Input --}}
        <div class="mb-6">
            <label class="block text-xs font-semibold text-text-muted uppercase tracking-wide mb-2">
                Quantità azioni
            </label>
            <input 
                type="number" 
                id="quantity-input" 
                min="1" 
                step="1" 
                value="1"
                class="w-full bg-surface-200 border-2 border-surface-200 rounded-xl px-4 py-4
                       text-3xl font-bold font-mono text-center
                       focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20
                       transition-all">
        </div>

        {{-- Shortcuts --}}
        <div class="grid grid-cols-4 gap-2 mb-6">
            <button data-percent="25" class="shortcut-btn py-3 rounded-lg bg-surface-200 hover:bg-surface-200/80 font-semibold text-sm transition-colors">
                25%
            </button>
            <button data-percent="50" class="shortcut-btn py-3 rounded-lg bg-surface-200 hover:bg-surface-200/80 font-semibold text-sm transition-colors">
                50%
            </button>
            <button data-percent="75" class="shortcut-btn py-3 rounded-lg bg-surface-200 hover:bg-surface-200/80 font-semibold text-sm transition-colors">
                75%
            </button>
            <button data-percent="100" class="shortcut-btn py-3 rounded-lg bg-surface-200 hover:bg-surface-200/80 font-semibold text-sm transition-colors">
                MAX
            </button>
        </div>

        {{-- Cost Summary Accordion --}}
        <div class="mb-6">
            <button 
                id="cost-accordion-toggle"
                class="w-full flex items-center justify-between py-3 text-left">
                <span class="text-sm font-semibold text-text-muted">Dettagli costi</span>
                <span class="material-icons text-text-muted transition-transform" id="accordion-icon" aria-hidden="true">
                    expand_more
                </span>
            </button>
            
            <div id="cost-accordion-content" class="hidden bg-surface-50/30 rounded-lg p-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-text-muted">Subtotale</span>
                    <span id="cost-subtotal" class="font-mono text-text-main">0.00 CFU</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-text-muted">Fee (2%)</span>
                    <span id="cost-fee" class="font-mono text-text-main">0.00 CFU</span>
                </div>
                <div class="h-px bg-surface-200 my-2"></div>
                <div class="flex justify-between font-bold text-text-main">
                    <span>Totale</span>
                    <span id="cost-total" class="font-mono text-lg text-text-main">0.00 CFU</span>
                </div>
            </div>
        </div>

        {{-- Loading State --}}
        <div id="modal-loading" class="hidden text-center py-4">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-brand"></div>
            <p class="text-sm text-text-muted mt-2">Caricamento...</p>
        </div>

        {{-- CTA Button --}}
        <button 
            id="btn-confirm-order" 
            class="btn-primary w-full text-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <span id="btn-spinner" class="hidden">
                <svg aria-hidden="true" class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </span>
            <span id="btn-text">Conferma Acquisto</span>
        </button>
    </div>
</div>
