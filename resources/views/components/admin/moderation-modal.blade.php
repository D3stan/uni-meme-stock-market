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
            />
        </div>

        {{-- Text Alternative (for future implementation) --}}
        <div class="mb-6 p-4 bg-surface-100 rounded-lg border border-surface-200">
            <h3 class="text-sm font-semibold text-text-muted mb-2">Alternativa Testuale</h3>
            <p id="meme-alt-text" class="text-text-muted text-sm">
                [Campo da implementare nel database]
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex justify-center gap-4">
            <form id="rejectForm" method="POST" class="flex-1">
                @csrf
                <x-forms.button type="submit" variant="danger" size="lg" class="w-full">
                    <span class="material-icons text-lg" aria-hidden="true">close</span>
                    Rifiuta
                </x-forms.button>
            </form>
            
            <form id="approveForm" method="POST" class="flex-1">
                @csrf
                <x-forms.button type="submit" variant="primary" size="lg" class="w-full">
                    <span class="material-icons text-lg" aria-hidden="true">check</span>
                    Approva
                </x-forms.button>
            </form>
        </div>
    </div>
</x-ui.modal>