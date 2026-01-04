{{-- Sticky Action Bar at Bottom --}}
@props(['meme'])

<div class="fixed bottom-0 left-0 right-0 bg-gray-900 border-t border-gray-800 p-4 z-40">
    <div class="flex gap-3 max-w-xl mx-auto">
        {{-- Sell Button --}}
        <button 
            id="btn-sell" 
            data-action="sell"
            class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl font-bold text-lg
                bg-red-600/20 border-2 border-red-600 text-red-400 hover:bg-red-600/30 transition-all">
            <span class="material-icons">sell</span>
            <span>VENDI</span>
        </button>

        {{-- Buy Button --}}
        <button 
            id="btn-buy" 
            data-action="buy"
            class="flex-1 flex items-center justify-center gap-2 py-4 rounded-xl font-bold text-lg
                bg-green-500 text-gray-900 hover:bg-green-400 transition-all">
            <span class="material-icons">shopping_cart</span>
            <span>COMPRA</span>
        </button>
    </div>
</div>

{{-- Spacer to prevent content from being hidden behind fixed bar --}}
<div class="h-24"></div>
