{{-- Moderation Modal --}}
<x-ui.modal id="moderation-modal" maxWidth="2xl">
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 id="moderationModal-title" class="text-2xl font-bold text-text-main">Modera Meme</h2>
            <button onclick="hideModal('moderation-modal')" aria-label="Chiudi" class="text-text-muted hover:text-text-main transition-colors">
                <span class="material-icons" aria-hidden="true">close</span>
            </button>
        </div>

        {{-- Meme Card --}}
        <div id="meme-card-container" class="mb-6">
            <x-meme.card 
                id="moderation-card"
                image=""
                alt=""
                name=""
                ticker=""
                :price="0"
                :change="0"
                creatorAvatar=""
                creatorName=""
                status=""
                tradeUrl="#"
                class="lg:w-full"
            />
        </div>

        {{-- Text Alternative --}}
        <div class="mb-6 p-4 bg-surface-100 rounded-lg border border-surface-200">
            <x-forms.textarea for="meme-alt-text" title="Alternativa Testuale" id="meme-alt-text" name="text_alt" 
                placeholder="Descrivi il contenuto del meme per gli screen reader..." 
            />
        </div>

        {{-- Actions --}}
        <div class="flex justify-center gap-4">
            <form id="rejectForm" method="POST" class="flex-1">
                @csrf
                <x-forms.button type="submit" variant="danger" size="lg" class="w-full">
                    <span class="material-icons text-lg">close</span>
                    Rifiuta
                </x-forms.button>
            </form>
            
            <form id="approveForm" method="POST" class="flex-1">
                @csrf
                <input type="hidden" id="text-alt-hidden" name="text_alt" value="">
                <x-forms.button type="submit" variant="primary" size="lg" class="w-full">
                    <span class="material-icons text-lg">check</span>
                    Approva
                </x-forms.button>
            </form>
        </div>
    </div>
</x-ui.modal>
