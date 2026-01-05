<x-admin title="Moderazione Meme" label="Indietro" icon="arrow_back" href="{{ route('admin.admin') }}">

    {{-- Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-ui.stat-card title="Totali" :value="number_format($stats['total'])" color="text-main" />
        <x-ui.stat-card title="In Attesa" :value="number_format($stats['pending'])" color="brand-accent" />
        <x-ui.stat-card title="Approvati" :value="number_format($stats['approved'])" color="brand" />
        <x-ui.stat-card title="Rifiutati" :value="number_format($stats['suspended'])" color="brand-danger" />
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
                'render' => fn($row) => '<span class="text-text-muted">#' . $row->id . '</span>'
            ],
            [
                'label' => 'Creato',
                'key' => 'created_at',
                'render' => fn($row) => $row->created_at->format('d/m/Y H:i')
            ],
            [
                'label' => 'Ticker',
                'key' => 'ticker',
                'render' => fn($row) => '<span class="text-text-main font-mono font-bold">$' . $row->ticker . '</span>'
            ],
            [
                'label' => 'Nome',
                'key' => 'title',
                'wrap' => true,
                'render' => fn($row) => '<span class="text-text-main font-medium">' . htmlspecialchars($row->title) . '</span>'
            ],
            [
                'label' => 'Creatore',
                'key' => 'creator.name',
                'render' => fn($row) => '<span class="text-text-muted">' . $row->creator->name . '</span>'
            ],
            [
                'label' => 'Categoria',
                'key' => 'category.name',
                'render' => fn($row) => '<span class="text-text-muted">' . $row->category->name . '</span>'
            ],
            [
                'label' => 'Stato',
                'key' => 'status',
                'align' => 'center',
                'render' => function($row) {
                    return match($row->status) {
                        'pending' => '<span class="badge-info">IN ATTESA</span>',
                        'approved' => '<span class="badge-positive">APPROVATO</span>',
                        'suspended' => '<span class="badge-negative">RIFIUTATO</span>',
                        default => '<span class="badge-neutral">' . strtoupper($row->status) . '</span>',
                    };
                }
            ],
            [
                'label' => 'Azioni',
                'key' => 'actions',
                'align' => 'center',
                'render' => fn($row) => '
                <button 
                    class="view-meme-btn p-2 hover:bg-surface-200 rounded-lg transition-colors" 
                    data-id="' . $row->id . '"
                    data-ticker="' . htmlspecialchars($row->ticker) . '"
                    data-title="' . htmlspecialchars($row->title) . '"
                    data-image="' . htmlspecialchars($row->image_path) . '"
                    data-text-alt="' . htmlspecialchars($row->text_alt ?? '') . '"
                    data-creator="' . htmlspecialchars($row->creator->name) . '"
                    data-creator-id="' . $row->creator_id . '"
                    data-creator-avatar="' . htmlspecialchars($row->creator->avatarUrl()) . '"
                    data-price="' . $row->current_price . '"
                    data-status="' . $row->status . '"
                    title="Visualizza meme"
                    aria-label="Visualizza #' . $row->id . '">
                    <span aria-hidden="true" class="material-icons text-text-muted hover:text-text-main text-xl">visibility</span>
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