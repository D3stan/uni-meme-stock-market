<x-admin title="Moderazione Meme" label="Indietro" icon="arrow_back" href="{{ route('admin.admin') }}">

    {{-- Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-ui.stat-card title="Totali" :value="number_format($stats['total'])" color="white" />
        <x-ui.stat-card title="In Attesa" :value="number_format($stats['pending'])" color="yellow-500" />
        <x-ui.stat-card title="Approvati" :value="number_format($stats['approved'])" color="green-500" />
        <x-ui.stat-card title="Rifiutati" :value="number_format($stats['suspended'])" color="red-500" />
    </div>

    {{-- Filters --}}
    <nav class="flex gap-2 mb-6 overflow-x-auto pb-2">
        <x-ui.chip :href="route('admin.moderation', ['filter' => 'all'])" :active="$currentFilter === 'all'">Tutti</x-ui.chip>
        <x-ui.chip :href="route('admin.moderation', ['filter' => 'pending'])" :active="$currentFilter === 'pending'">In Attesa</x-ui.chip>
        <x-ui.chip :href="route('admin.moderation', ['filter' => 'approved'])" :active="$currentFilter === 'approved'">Approvati</x-ui.chip>
        <x-ui.chip :href="route('admin.moderation', ['filter' => 'suspended'])" :active="$currentFilter === 'suspended'">Rifiutati</x-ui.chip>
    </nav>

    {{-- Memes Table --}}
    @php
        $columns = [
            [
                'label' => 'ID',
                'key' => 'id',
                'render' => fn($row) => '<span class="text-gray-400">#' . $row->id . '</span>'
            ],
            [
                'label' => 'Creato',
                'key' => 'created_at',
                'render' => fn($row) => $row->created_at->format('d/m/Y H:i')
            ],
            [
                'label' => 'Ticker',
                'key' => 'ticker',
                'render' => fn($row) => '<span class="text-white font-mono font-bold">$' . $row->ticker . '</span>'
            ],
            [
                'label' => 'Nome',
                'key' => 'title',
                'wrap' => true,
                'render' => fn($row) => '<span class="text-white font-medium">' . htmlspecialchars($row->title) . '</span>'
            ],
            [
                'label' => 'Creatore',
                'key' => 'creator.name',
                'render' => fn($row) => '<span class="text-gray-300">' . $row->creator->name . '</span>'
            ],
            [
                'label' => 'Categoria',
                'key' => 'category.name',
                'render' => fn($row) => '<span class="text-gray-300">' . $row->category->name . '</span>'
            ],
            [
                'label' => 'Stato',
                'key' => 'status',
                'align' => 'center',
                'render' => function($row) {
                    return match($row->status) {
                        'pending' => '<span class="px-2 py-1 bg-yellow-600/20 text-yellow-500 rounded-full text-xs font-semibold">IN ATTESA</span>',
                        'approved' => '<span class="px-2 py-1 bg-green-600/20 text-green-500 rounded-full text-xs font-semibold">APPROVATO</span>',
                        'suspended' => '<span class="px-2 py-1 bg-red-600/20 text-red-500 rounded-full text-xs font-semibold">RIFIUTATO</span>',
                        default => '<span class="px-2 py-1 bg-gray-600/20 text-gray-500 rounded-full text-xs font-semibold">' . strtoupper($row->status) . '</span>',
                    };
                }
            ],
            [
                'label' => 'Azioni',
                'key' => 'actions',
                'align' => 'center',
                'render' => fn($row) => '
                <button 
                    class="view-meme-btn p-2 hover:bg-gray-800 rounded-lg transition-colors" 
                    data-id="' . $row->id . '"
                    data-ticker="' . htmlspecialchars($row->ticker) . '"
                    data-title="' . htmlspecialchars($row->title) . '"
                    data-image="' . htmlspecialchars($row->image_path) . '"
                    data-text-alt="' . htmlspecialchars($row->text_alt ?? '') . '"
                    data-creator="' . htmlspecialchars($row->creator->name) . '"
                    data-creator-id="' . $row->creator_id . '"
                    data-creator-avatar="' . htmlspecialchars($row->creator->avatar ?? 'https://via.placeholder.com/40') . '"
                    data-price="' . $row->current_price . '"
                    data-status="' . $row->status . '"
                    title="Visualizza meme"
                    aria-label="Visualizza #' . $row->id . '">
                    <span aria-hidden="true" class="material-icons text-gray-400 hover:text-white text-xl">visibility</span>
                </button>'
            ],
        ];
    @endphp

    <x-ui.table :columns="$columns" :rows="$memes" :paginate="true" caption="Meme caricati dagli utenti" emptyMessage="Nessun meme trovato" />

    <x-admin.moderation-modal/>

    @push('page-scripts')
    <script src="{{ asset('js/admin/moderation.js') }}"></script>
    @endpush

</x-admin>