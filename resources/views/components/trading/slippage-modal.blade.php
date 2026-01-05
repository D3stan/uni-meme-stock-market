{{-- Slippage Alert Modal --}}

<!-- Slippage Modal Backdrop -->
<div id="slippage-modal-backdrop" class="fixed inset-0 bg-black/80 z-[60] hidden"></div>

<!-- Slippage Modal -->
<div id="slippage-modal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 hidden">
    <div class="bg-surface-100 rounded-2xl max-w-sm w-full p-6 border border-brand-accent/30">
        {{-- Warning Icon --}}
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-brand-accent/20 rounded-full flex items-center justify-center">
                <span class="material-icons text-4xl text-brand-accent">warning</span>
            </div>
        </div>

        {{-- Title --}}
        <h3 class="text-2xl font-bold text-center mb-2 text-text-main">
            Prezzo cambiato!
        </h3>

        {{-- Message --}}
        <p class="text-center text-text-muted text-sm mb-4">
            Il prezzo del meme è variato mentre preparavi l'ordine. Controlla i nuovi valori e riprova.
        </p>

        {{-- Price Comparison --}}
        <div class="bg-surface-200 rounded-lg p-4 mb-6 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-text-muted">Prezzo Previsto:</span>
                <span id="slippage-expected" class="font-mono">0.00 CFU</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-text-muted">Prezzo Nuovo:</span>
                <span id="slippage-actual" class="font-mono font-semibold text-brand">0.00 CFU</span>
            </div>
            <div class="h-px bg-surface-200 my-2"></div>
            <div class="flex justify-between font-bold">
                <span>Variazione:</span>
                <span id="slippage-change" class="font-mono"></span>
            </div>
        </div>

        {{-- Action Button --}}
        <button 
            id="btn-close-slippage" 
            class="btn-primary w-full">
            Ho capito
        </button>
    </div>
</div>
