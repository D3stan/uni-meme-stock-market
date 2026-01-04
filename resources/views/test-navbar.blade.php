<x-app :active="'create'" :balance="'1,250.00'">
    <div class="pb-20 lg:pb-8 lg:pt-20">
        <div class="max-w-4xl mx-auto px-4 py-8 space-y-12">
            
            {{-- Header --}}
            <div class="text-center space-y-4">
                <h1 class="text-4xl font-black text-green-500">Navigation Bar Test</h1>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 rounded-lg text-sm">
                    <span class="material-icons text-green-500 text-base">info</span>
                    <span>Ridimensiona la finestra per vedere la versione desktop</span>
                </div>
            </div>
        </div>
    </div>
</x-app>
