<x-admin title="Gestione Notifiche" label="Indietro" icon="arrow_back" href="{{ route('admin.admin') }}">

    {{-- Statistics --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <x-ui.stat-card title="Totali" :value="number_format($stats['total'])" color="white" />
        <x-ui.stat-card title="Lette" :value="number_format($stats['read'])" color="green-500" />
        <x-ui.stat-card title="Non Lette" :value="number_format($stats['unread'])" color="yellow-500" />
        <x-ui.stat-card title="Globali" :value="number_format($stats['global'])" color="blue-500" />
        <x-ui.stat-card title="Personali" :value="number_format($stats['personal'])" color="purple-500" />
    </div>

    {{-- Filters --}}
    <nav class="flex gap-2 mb-6 overflow-x-auto pb-2">
        <x-ui.chip :href="route('admin.notifications', ['filter' => 'all'])" :active="$currentFilter === 'all'">Tutte</x-ui.chip>
        <x-ui.chip :href="route('admin.notifications', ['filter' => 'unread'])" :active="$currentFilter === 'unread'">Non Lette</x-ui.chip>
        <x-ui.chip :href="route('admin.notifications', ['filter' => 'read'])" :active="$currentFilter === 'read'">Lette</x-ui.chip>
        <x-ui.chip :href="route('admin.notifications', ['filter' => 'global'])" :active="$currentFilter === 'global'">Globali</x-ui.chip>
    </nav>

    {{-- Notifications Table --}}
    @php
        $columns = [
            [
                'label' => 'ID',
                'key' => 'id',
                'render' => fn($row) => '<span class="text-gray-400">#' . $row->id . '</span>'
            ],
            [
                'label' => 'Data',
                'key' => 'created_at',
                'render' => fn($row) => $row->created_at->format('d/m/Y H:i')
            ],
            [
                'label' => 'Utente',
                'key' => 'user',
                'render' => fn($row) => $row->user 
                    ? '<span class="text-white font-medium">' . $row->user->name . '</span>' 
                    : '<span class="px-2 py-1 bg-blue-600/20 text-blue-500 rounded-full text-xs font-semibold">GLOBALE</span>'
            ],
            [
                'label' => 'Titolo',
                'key' => 'title',
                'render' => fn($row) => '<span class="text-white font-semibold">' . htmlspecialchars($row->title) . '</span>'
            ],
            [
                'label' => 'Messaggio',
                'key' => 'message',
                'render' => fn($row) => '<span class="text-gray-300">' . htmlspecialchars(str($row->message)->limit(50)) . '</span>'
            ],
            [
                'label' => 'Stato',
                'key' => 'is_read',
                'align' => 'center',
                'render' => function($row) {
                    if ($row->is_read) {
                        return '<span class="px-2 py-1 bg-green-600/20 text-green-500 rounded-full text-xs font-semibold">LETTA</span>';
                    } else {
                        return '<span class="px-2 py-1 bg-yellow-600/20 text-yellow-500 rounded-full text-xs font-semibold">NON LETTA</span>';
                    }
                }
            ],
        ];
    @endphp

    <x-ui.table :columns="$columns" :rows="$notifications" :paginate="true" caption="Notifiche inviate agli utenti" emptyMessage="Nessuna notifica trovata" />

</x-admin>