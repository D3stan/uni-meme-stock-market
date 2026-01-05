<x-app active="create" title="Upload" :balance="$balance">
    
    <div class="max-w-2xl mx-auto pb-24">
        <form id="uploadMemeForm" action="{{ route('meme.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Upload Area --}}
            <x-forms.filepicker
                id="memeImage"
                name="image"
                accept="image/*"
                emptyText="Tocca per caricare un meme"
                formats=".JPG, .PNG .WEBP supportati"
                maxSize="5MB"
            />

            {{-- Form Data --}}
            <div class="px-4 space-y-4">
                {{-- Title --}}
                <x-forms.textinput
                    id="title"
                    name="title"
                    label="Titolo del Meme"
                    placeholder="es. Io quando navigo il sito dell'Unibo"
                    maxlength="50"
                    required
                />

                {{-- Ticker --}}
                <x-forms.textinput
                    id="ticker"
                    name="ticker"
                    label="Ticker"
                    prefix="$"
                    placeholder="UNIBO"
                    maxlength="6"
                    helpText="Sarà univoco e verificato"
                    required
                    class="font-mono uppercase"
                />

                {{-- Category --}}
                <x-forms.select
                    id="category"
                    name="category_id"
                    label="Categoria"
                    required="true" 
                    :options="$categories->map(fn($cat) => ['value' => $cat->id, 'text' => $cat->name])->toArray()"
                />
            </div>

            {{-- Billing --}}
            <div class="px-4">
                <div class="bg-gray-800 rounded-lg p-4 space-y-3 border border-gray-700">
                    <div class="flex items-center gap-2 mb-3">
                        <span aria-hidden="true" class="material-icons text-green-500 text-lg">receipt</span>
                        <h3 class="text-sm font-semibold text-white uppercase">Riepilogo Finanziario</h3>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Tuo saldo attuale</span>
                        <span class="text-sm font-mono text-white">{{ number_format($balance, 2) }} CFU</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Costo Listing (Fee)</span>
                        <span class="text-sm font-mono text-red-400">- 20.00 CFU</span>
                    </div>

                    <div class="border-t border-gray-700 pt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-white">Saldo dopo listing</span>
                            <span class="text-base font-mono font-bold {{ $balance >= 20 ? 'text-green-500' : 'text-red-500' }}">
                                {{ number_format($balance - 20, 2) }} CFU
                            </span>
                        </div>
                    </div>

                    @if($balance < 20)
                        <div class="bg-red-600/20 border border-red-600/30 rounded-lg p-3 flex items-center gap-2">
                            <span aria-hidden="true" class="material-icons text-red-400 text-lg">warning</span>
                            <span class="text-sm text-red-400 font-medium">Saldo insufficiente</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CTA Principale --}}
            <div class="px-4">
                <x-forms.button 
                    type="submit" 
                    variant="success" 
                    size="lg" 
                    class="w-full"
                    id="submitBtn"
                >
                <span aria-hidden="true" class="material-icons text-lg">send</span>
                    Paga e Invia al Rettorato
                </x-forms.button>

                <p class="mt-3 text-xs text-gray-500 text-center">
                    La fee non è rimborsabile. Il meme sarà controllato dall'admin.
                </p>
            </div>
        </form>
    </div>

    {{-- Notification Modal --}}
    <x-ui.notify-modal />

    @push('page-scripts')
    <script src="{{ asset('js/create.js') }}"></script>
    @endpush

</x-app>