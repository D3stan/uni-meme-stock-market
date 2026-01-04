{{-- Slippage Alert Modal --}}

<!-- Slippage Modal Backdrop -->
<div id="slippage-modal-backdrop" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[60] hidden"></div>

<!-- Slippage Modal -->
<div id="slippage-modal" class="fixed inset-0 z-[70] flex items-center justify-center p-4 hidden">
    <div class="bg-gray-900 rounded-2xl max-w-sm w-full p-6 border border-yellow-500/30">
        {{-- Warning Icon --}}
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center">
                <span class="material-icons text-4xl text-yellow-500">warning</span>
            </div>
        </div>

        {{-- Title --}}
        <h3 class="text-2xl font-bold text-center mb-2">
            Prezzo cambiato!
        </h3>

        {{-- Message --}}
        <p class="text-center text-gray-400 text-sm mb-4">
            Il prezzo del meme è variato mentre preparavi l'ordine. Controlla i nuovi valori e riprova.
        </p>

        {{-- Price Comparison --}}
        <div class="bg-gray-800 rounded-lg p-4 mb-6 space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Prezzo Previsto:</span>
                <span id="slippage-expected" class="font-mono">0.00 CFU</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-400">Prezzo Nuovo:</span>
                <span id="slippage-actual" class="font-mono font-semibold text-green-400">0.00 CFU</span>
            </div>
            <div class="h-px bg-gray-700 my-2"></div>
            <div class="flex justify-between font-bold">
                <span>Variazione:</span>
                <span id="slippage-change" class="font-mono"></span>
            </div>
        </div>

        {{-- Action Button --}}
        <button 
            id="btn-close-slippage" 
            class="w-full py-3 rounded-xl bg-green-500 text-gray-900 font-bold hover:bg-green-400 transition-colors">
            Ho capito
        </button>
    </div>
</div>
