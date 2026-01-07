{{-- Sticky Action Bar at Bottom --}}
@props(['meme'])

<div class="fixed bottom-0 left-0 right-0 bg-surface-50 border-t border-surface-200 p-4 z-40">
    <div class="flex gap-3 max-w-xl mx-auto">
        {{-- Sell Button --}}
        <button 
            id="btn-sell" 
            data-action="sell"
            class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl font-bold text-lg
                bg-brand-danger/20 border-2 border-brand-danger text-brand-danger hover:bg-brand-danger/30 transition-all">
            <span class="material-icons" aria-hidden="true">sell</span>
            <span>VENDI</span>
        </button>

        {{-- Buy Button --}}
        <button 
            id="btn-buy" 
            data-action="buy"
            class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl font-bold text-lg
                bg-brand text-surface-50 hover:bg-brand-light transition-all">
            <span class="material-icons" aria-hidden="true">shopping_cart</span>
            <span>COMPRA</span>
        </button>
    </div>
</div>

{{-- Spacer to prevent content from being hidden behind fixed bar --}}
<div class="h-24"></div>
