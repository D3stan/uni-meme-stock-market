<x-admin title="Gestione Eventi" label="Indietro" icon="arrow_back" href="{{ route('admin.admin') }}">

    {{-- Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <x-ui.stat-card title="Totali" :value="number_format($stats['total'])" color="white" />
        <x-ui.stat-card title="Attivi" :value="number_format($stats['active'])" color="green-500" />
        <x-ui.stat-card title="Scaduti" :value="number_format($stats['expired'])" color="red-500" />
        <x-ui.stat-card title="Permanenti" :value="number_format($stats['permanent'])" color="blue-500" />
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
                'render' => fn($row) => '<span class="text-gray-400">#' . $row->id . '</span>'
            ],
            [
                'label' => 'Data Creazione',
                'key' => 'created_at',
                'render' => fn($row) => $row->created_at->format('d/m/Y H:i')
            ],
            [
                'label' => 'Admin',
                'key' => 'admin.name',
                'render' => fn($row) => '<span class="text-white font-medium">' . $row->admin->name . '</span>'
            ],
            [
                'label' => 'Messaggio',
                'key' => 'message',
                'wrap' => true,
                'render' => fn($row) => '<span class="text-gray-300">' . htmlspecialchars(str($row->message)) . '</span>'
            ],
            [
                'label' => 'Scadenza',
                'key' => 'expires_at',
                'render' => fn($row) => $row->expires_at 
                    ? '<span class="text-gray-300">' . $row->expires_at->format('d/m/Y H:i') . '</span>'
                    : '<span class="px-2 py-1 bg-blue-600/20 text-blue-500 rounded-full text-xs font-semibold">PERMANENTE</span>'
            ],
            [
                'label' => 'Stato',
                'key' => 'is_active',
                'align' => 'center',
                'render' => function($row) {
                    $isActive = $row->is_active && (!$row->expires_at || $row->expires_at->isFuture());
                    if ($isActive) {
                        return '<span class="px-2 py-1 bg-green-600/20 text-green-500 rounded-full text-xs font-semibold">ATTIVO</span>';
                    } else {
                        return '<span class="px-2 py-1 bg-red-600/20 text-red-500 rounded-full text-xs font-semibold">SCADUTO</span>';
                    }
                }
            ],
            [
                'label' => 'Azioni',
                'key' => 'actions',
                'align' => 'center',
                'render' => fn($row) => '<button class="p-2 hover:bg-gray-800 rounded-lg transition-colors" title="Modifica evento">
                    <span class="material-icons text-gray-400 hover:text-white text-xl">edit</span>
                </button>'
            ],
        ];
    @endphp

    <x-ui.table :columns="$columns" :rows="$communications" :paginate="true" caption="Comunicazioni di mercato inviate agli utenti" emptyMessage="Nessuna comunicazione trovata" />

</x-admin>