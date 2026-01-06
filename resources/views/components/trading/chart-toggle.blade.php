{{-- Toggle between Chart and Meme Image View --}}
@props(['meme'])

<div class="flex justify-center gap-2 px-4 mb-4">
    <button 
        id="btn-chart-view" 
        class="flex items-center gap-2 px-6 py-2.5 rounded-full font-semibold transition-all
            bg-brand text-surface-50">
        <span>Grafico</span>
    </button>
    <button 
        id="btn-meme-view" 
        class="flex items-center gap-2 px-6 py-2.5 rounded-full font-semibold transition-all
            bg-surface-200 text-text-muted hover:bg-surface-200/80">
        <span>Meme</span>
    </button>
</div>

{{-- Chart Container --}}
<div id="chart-container" class="px-4 mb-6">
    <div id="chart" class="w-full h-72 bg-surface-100/50 rounded-lg"></div>
    
    {{-- Time Period Selector --}}
    <div class="flex justify-center gap-3 mt-4">
        <button data-period="1h" class="period-btn px-6 py-2 rounded-full text-sm font-medium
            bg-surface-200 text-text-muted hover:bg-surface-200/80 transition-colors">
            1H
        </button>
        <button data-period="4h" class="period-btn px-6 py-2 rounded-full text-sm font-medium
            bg-surface-200 text-text-muted hover:bg-surface-200/80 transition-colors">
            4H
        </button>
        <button data-period="1d" class="period-btn px-6 py-2 rounded-full text-sm font-medium
            bg-brand text-surface-50 transition-colors">
            1D
        </button>
    </div>
</div>

{{-- Meme Image Container (Initially Hidden) --}}
<div id="meme-container" class="px-4 mb-6 hidden">
    {{-- Meme Title and Category --}}
    <div class="mb-4 space-y-2">
        <h2 class="text-2xl font-bold text-text-main">{{ $meme->title }}</h2>
        @if($meme->category)
            <div class="flex items-center gap-2">
                <span class="text-sm text-text-muted">Categoria:</span>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-brand/10 text-brand">
                    {{ $meme->category->name }}
                </span>
            </div>
        @endif
    </div>

    {{-- Meme Image --}}
    <div class="bg-surface-100/50 rounded-lg overflow-hidden">
        @if($meme->image_path)
            <img src="{{ asset('storage/data/' . $meme->creator_id . '/' . basename($meme->image_path)) }}" 
                 alt="{{ $meme->text_alt }}" 
                 class="w-full h-auto"
                 onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-72 text-text-muted\'><span class=\'material-icons text-6xl\'>Immagine non supportata</span></div>'">
        @else
            <div class="flex items-center justify-center h-72 text-text-muted">
                <span class="material-icons text-6xl">Immagine non supportata</span>
            </div>
        @endif
    </div>
</div>
