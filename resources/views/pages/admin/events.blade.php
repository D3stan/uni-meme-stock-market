<x-admin title="Gestione Eventi" label="Indietro" icon="arrow_back" href="{{ route('admin.admin') }}">

    {{-- Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-ui.stat-card title="Totali" :value="number_format($stats['total'])" color="text-main" />
        <x-ui.stat-card title="Attivi" :value="number_format($stats['active'])" color="brand" />
        <x-ui.stat-card title="Scaduti" :value="number_format($stats['expired'])" color="brand-danger" />
        <x-ui.stat-card title="Permanenti" :value="number_format($stats['permanent'])" color="brand-accent" />
    </div>

    {{-- Filters --}}
    <nav class="flex gap-2 mb-6 overflow-x-auto pb-2">
        <x-ui.chip :href="route('admin.events', ['filter' => 'all'])" :active="$currentFilter === 'all'">Tutti</x-ui.chip>
        <x-ui.chip :href="route('admin.events', ['filter' => 'active'])" :active="$currentFilter === 'active'">Attivi</x-ui.chip>
        <x-ui.chip :href="route('admin.events', ['filter' => 'expired'])" :active="$currentFilter === 'expired'">Scaduti</x-ui.chip>
    </nav>

    {{-- Communications Table --}}
    @php
        $columns = [
            [
                'label' => 'ID',
                'key' => 'id',
                'render' => fn($row) => '<span class="text-text-muted">#' . $row->id . '</span>'
            ],
            [
                'label' => 'Data Creazione',
                'key' => 'created_at',
                'render' => fn($row) => $row->created_at->format('d/m/Y H:i')
            ],
            [
                'label' => 'Admin',
                'key' => 'admin.name',
                'render' => fn($row) => '<span class="text-text-main font-medium">' . $row->admin->name . '</span>'
            ],
            [
                'label' => 'Messaggio',
                'key' => 'message',
                'wrap' => true,
                'class' => 'min-w-[60vw] md:min-w-0',
                'render' => fn($row) => '<span class="text-text-muted">' . htmlspecialchars(str($row->message)->limit(50)) . '</span>'
            ],
            [
                'label' => 'Scadenza',
                'key' => 'expires_at',
                'render' => fn($row) => $row->expires_at 
                    ? '<span class="text-text-muted">' . $row->expires_at->format('d/m/Y') . '<br>' . $row->expires_at->format('H:i') . '</span>'
                    : '<span class="badge-info">PERMANENTE</span>'
            ],
            [
                'label' => 'Stato',
                'key' => 'is_active',
                'align' => 'center',
                'render' => function($row) {
                    $isActive = $row->is_active && (!$row->expires_at || $row->expires_at->isFuture());
                    if ($isActive) {
                        return '<span class="badge-positive">ATTIVO</span>';
                    } else {
                        return '<span class="badge-negative">SCADUTO</span>';
                    }
                }
            ],
            [
                'label' => 'Azioni',
                'key' => 'actions',
                'align' => 'center',
                'render' => fn($row) => '
                <button 
                    class="edit-event-btn p-2 hover:bg-surface-200 rounded-lg transition-colors" 
                    data-id="' . $row->id . '"
                    data-message="' . htmlspecialchars($row->message) . '"
                    data-expires-at="' . ($row->expires_at ? $row->expires_at->format('Y-m-d\TH:i') : '') . '"
                    data-is-active="' . ($row->is_active ? '1' : '0') . '"
                    title="Modifica evento"
                    aria-label="Modifica #{{ $row->id }}" >
                    <span aria-hidden="true" class="material-icons text-text-muted hover:text-text-main text-xl">edit</span>
                </button>'
            ],
        ];
    @endphp

    <x-ui.table :columns="$columns" :rows="$communications" :paginate="true" caption="Comunicazioni di mercato inviate agli utenti" emptyMessage="Nessuna comunicazione trovata">
        <x-slot:actions>
            <x-forms.button onclick="openCreateModal()" variant="primary" size="lg">
                <span class="material-icons text-lg">add</span>
                Crea
            </x-forms.button>
        </x-slot:actions>
    </x-ui.table>

    <x-admin.event-modal/>

    @push('page-scripts')
        @vite(['resources/js/pages/admin/events.js'])
    @endpush

</x-admin>