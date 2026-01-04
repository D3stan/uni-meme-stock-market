{{-- Toggle between Chart and Meme Image View --}}
@props(['meme'])

<div class="flex justify-center gap-2 px-4 mb-4">
    <button 
        id="btn-chart-view" 
        class="flex items-center gap-2 px-6 py-2.5 rounded-full font-semibold transition-all
            bg-green-500 text-gray-900">
        <span class="material-icons text-lg">📊</span>
        <span>Grafico</span>
    </button>
    <button 
        id="btn-meme-view" 
        class="flex items-center gap-2 px-6 py-2.5 rounded-full font-semibold transition-all
            bg-gray-700 text-gray-300 hover:bg-gray-600">
        <span class="material-icons text-lg">🖼️</span>
        <span>Meme</span>
    </button>
</div>

{{-- Chart Container --}}
<div id="chart-container" class="px-4 mb-6">
    <div id="chart" class="w-full h-72 bg-gray-900/50 rounded-lg"></div>
    
    {{-- Time Period Selector --}}
    <div class="flex justify-center gap-3 mt-4">
        <button data-period="1h" class="period-btn px-6 py-2 rounded-full text-sm font-medium
            bg-gray-800 text-gray-400 hover:bg-gray-700 transition-colors">
            1H
        </button>
        <button data-period="4h" class="period-btn px-6 py-2 rounded-full text-sm font-medium
            bg-gray-800 text-gray-400 hover:bg-gray-700 transition-colors">
            4H
        </button>
        <button data-period="1d" class="period-btn px-6 py-2 rounded-full text-sm font-medium
            bg-gray-800 text-gray-400 hover:bg-gray-700 transition-colors">
            1D
        </button>
        <button data-period="30d" class="period-btn px-6 py-2 rounded-full text-sm font-medium
            bg-green-500 text-gray-900 transition-colors">
            30D
        </button>
    </div>
</div>

{{-- Meme Image Container (Initially Hidden) --}}
<div id="meme-container" class="px-4 mb-6 hidden">
    <div class="bg-gray-900/50 rounded-lg overflow-hidden">
        @if($meme->image_path)
            <img src="{{ asset('storage/data/' . $meme->creator_id . '/' . basename($meme->image_path)) }}" 
                 alt="{{ $meme->title }}" 
                 class="w-full h-auto"
                 onerror="this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-72 text-gray-500\'><span class=\'material-icons text-6xl\'>image_not_supported</span></div>'">
        @else
            <div class="flex items-center justify-center h-72 text-gray-500">
                <span class="material-icons text-6xl">image_not_supported</span>
            </div>
        @endif
    </div>
</div>
