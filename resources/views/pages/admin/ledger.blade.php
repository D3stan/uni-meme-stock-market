<x-admin title="Ledger di Mercato" label="Indietro" icon="arrow_back" href="{{ route('admin.admin') }}">

    {{-- Statistics --}}
    <section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        <x-ui.stat-card title="Totale" :value="number_format($stats['total'])" color="text-main" />
        <x-ui.stat-card title="Acquisti" :value="number_format($stats['buy'])" color="brand" />
        <x-ui.stat-card title="Vendite" :value="number_format($stats['sell'])" color="brand-danger" />
        <x-ui.stat-card title="Bonus" :value="number_format($stats['bonus'])" color="brand-accent" />
        <x-ui.stat-card title="Dividendi" :value="number_format($stats['dividend'])" color="brand-accent" />
        <x-ui.stat-card title="Volume" :value="'€' . number_format($stats['volume'], 0)" color="text-main" />
    </section>

    {{-- Filters --}}
    <nav class="flex gap-2 mb-6 overflow-x-auto pb-2">
        <x-ui.chip :href="route('admin.ledger', ['type' => 'all'])" :active="$currentType === 'all'">Tutte</x-ui.chip>
        <x-ui.chip :href="route('admin.ledger', ['type' => 'buy'])" :active="$currentType === 'buy'">Acquisti</x-ui.chip>
        <x-ui.chip :href="route('admin.ledger', ['type' => 'sell'])" :active="$currentType === 'sell'">Vendite</x-ui.chip>
        <x-ui.chip :href="route('admin.ledger', ['type' => 'bonus'])" :active="$currentType === 'bonus'">Bonus</x-ui.chip>
        <x-ui.chip :href="route('admin.ledger', ['type' => 'dividend'])" :active="$currentType === 'dividend'">Dividendi</x-ui.chip>
        <x-ui.chip :href="route('admin.ledger', ['type' => 'listing_fee'])" :active="$currentType === 'listing_fee'">Listing Fee</x-ui.chip>
    </nav>

    {{-- Transactions Table --}}
    @php
        $columns = [
            [
                'label' => 'ID',
                'key' => 'id',
                'render' => fn($row) => '<span class="text-text-muted">#' . $row->id . '</span>'
            ],
            [
                'label' => 'Data',
                'key' => 'executed_at',
                'render' => fn($row) => $row->executed_at->format('d/m/Y H:i')
            ],
            [
                'label' => 'Utente',
                'key' => 'user.name',
                'render' => fn($row) => '<span class="text-text-main font-medium">' . $row->user->name . '</span>'
            ],
            [
                'label' => 'Tipo',
                'key' => 'type',
                'render' => function($row) {
                    $badges = [
                        'buy' => '<span class="badge-positive">ACQUISTO</span>',
                        'sell' => '<span class="badge-negative">VENDITA</span>',
                        'bonus' => '<span class="badge-info">BONUS</span>',
                        'dividend' => '<span class="badge-info">DIVIDENDO</span>',
                        'listing_fee' => '<span class="badge-neutral">LISTING FEE</span>',
                    ];
                    return $badges[$row->type] ?? '';
                }
            ],
            [
                'label' => 'Meme',
                'key' => 'meme',
                'render' => fn($row) => $row->meme 
                    ? '<span class="font-semibold">$' . $row->meme->ticker . '</span>' 
                    : '<span class="text-text-muted">-</span>'
            ],
            [
                'label' => 'Quantità',
                'key' => 'quantity',
                'align' => 'right',
                'render' => fn($row) => $row->quantity 
                    ? number_format($row->quantity) 
                    : '<span class="text-text-muted">-</span>'
            ],
            [
                'label' => 'Prezzo',
                'key' => 'price_per_share',
                'align' => 'right',
                'render' => fn($row) => $row->price_per_share 
                    ? '€' . number_format($row->price_per_share, 2) 
                    : '<span class="text-text-muted">-</span>'
            ],
            [
                'label' => 'Fee',
                'key' => 'fee_amount',
                'align' => 'right',
                'render' => fn($row) => '<span class="text-text-muted">€' . number_format($row->fee_amount, 2) . '</span>'
            ],
            [
                'label' => 'Totale',
                'key' => 'total_amount',
                'align' => 'right',
                'render' => function($row) {
                    $isNegative = $row->type === 'buy' || $row->type === 'listing_fee';
                    $color = $isNegative ? 'text-brand-danger' : 'text-brand';
                    $sign = $isNegative ? '-' : '+';
                    return '<span class="font-semibold ' . $color . '">' . $sign . '€' . number_format(abs($row->total_amount), 2) . '</span>';
                }
            ],
            [
                'label' => 'Saldo Dopo',
                'key' => 'cfu_balance_after',
                'align' => 'right',
                'render' => fn($row) => '<span class="text-text-main font-medium">€' . number_format($row->cfu_balance_after, 2) . '</span>'
            ],
        ];
    @endphp

    <x-ui.table :columns="$columns" :rows="$transactions" :paginate="true" caption="Transazioni eseguite dagli utenti" emptyMessage="Nessuna transazione trovata" />

</x-admin>