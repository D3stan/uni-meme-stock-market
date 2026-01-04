<x-admin>
    <div class="min-h-screen pb-8 pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-white">Ledger di Mercato</h1>
                <a href="{{ route('admin.admin') }}" aria-label="Indietro" class="p-2 hover:bg-gray-800 rounded-lg transition-colors">
                    <span class="material-icons text-white text-3xl" aria-hidden="true">arrow_back</span>
                </a>
            </div>

            {{-- Statistics --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <x-ui.stat-card title="Totale" :value="number_format($stats['total'])" color="white" />
                <x-ui.stat-card title="Acquisti" :value="number_format($stats['buy'])" color="green-500" />
                <x-ui.stat-card title="Vendite" :value="number_format($stats['sell'])" color="red-500" />
                <x-ui.stat-card title="Bonus" :value="number_format($stats['bonus'])" color="blue-500" />
                <x-ui.stat-card title="Dividendi" :value="number_format($stats['dividend'])" color="purple-500" />
                <x-ui.stat-card title="Volume" :value="'€' . number_format($stats['volume'], 0)" color="white" />
            </div>

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
                        'render' => fn($row) => '<span class="text-gray-400">#' . $row->id . '</span>'
                    ],
                    [
                        'label' => 'Data',
                        'key' => 'executed_at',
                        'render' => fn($row) => $row->executed_at->format('d/m/Y H:i')
                    ],
                    [
                        'label' => 'Utente',
                        'key' => 'user.name',
                        'render' => fn($row) => '<span class="text-white font-medium">' . $row->user->name . '</span>'
                    ],
                    [
                        'label' => 'Tipo',
                        'key' => 'type',
                        'render' => function($row) {
                            $badges = [
                                'buy' => '<span class="px-2 py-1 bg-green-600/20 text-green-500 rounded-full text-xs font-semibold">ACQUISTO</span>',
                                'sell' => '<span class="px-2 py-1 bg-red-600/20 text-red-500 rounded-full text-xs font-semibold">VENDITA</span>',
                                'bonus' => '<span class="px-2 py-1 bg-blue-600/20 text-blue-500 rounded-full text-xs font-semibold">BONUS</span>',
                                'dividend' => '<span class="px-2 py-1 bg-purple-600/20 text-purple-500 rounded-full text-xs font-semibold">DIVIDENDO</span>',
                                'listing_fee' => '<span class="px-2 py-1 bg-yellow-600/20 text-yellow-500 rounded-full text-xs font-semibold">LISTING FEE</span>',
                            ];
                            return $badges[$row->type] ?? '';
                        }
                    ],
                    [
                        'label' => 'Meme',
                        'key' => 'meme',
                        'render' => fn($row) => $row->meme 
                            ? '<span class="font-semibold">$' . $row->meme->ticker . '</span>' 
                            : '<span class="text-gray-500">-</span>'
                    ],
                    [
                        'label' => 'Quantità',
                        'key' => 'quantity',
                        'align' => 'right',
                        'render' => fn($row) => $row->quantity 
                            ? number_format($row->quantity) 
                            : '<span class="text-gray-500">-</span>'
                    ],
                    [
                        'label' => 'Prezzo',
                        'key' => 'price_per_share',
                        'align' => 'right',
                        'render' => fn($row) => $row->price_per_share 
                            ? '€' . number_format($row->price_per_share, 2) 
                            : '<span class="text-gray-500">-</span>'
                    ],
                    [
                        'label' => 'Fee',
                        'key' => 'fee_amount',
                        'align' => 'right',
                        'render' => fn($row) => '<span class="text-gray-400">€' . number_format($row->fee_amount, 2) . '</span>'
                    ],
                    [
                        'label' => 'Totale',
                        'key' => 'total_amount',
                        'align' => 'right',
                        'render' => function($row) {
                            $isNegative = $row->type === 'buy' || $row->type === 'listing_fee';
                            $color = $isNegative ? 'text-red-500' : 'text-green-500';
                            $sign = $isNegative ? '-' : '+';
                            return '<span class="font-semibold ' . $color . '">' . $sign . '€' . number_format(abs($row->total_amount), 2) . '</span>';
                        }
                    ],
                    [
                        'label' => 'Saldo Dopo',
                        'key' => 'cfu_balance_after',
                        'align' => 'right',
                        'render' => fn($row) => '<span class="text-white font-medium">€' . number_format($row->cfu_balance_after, 2) . '</span>'
                    ],
                ];
            @endphp

            <x-ui.table :columns="$columns" :rows="$transactions" :paginate="true" caption="Transazioni eseguite dagli utenti" emptyMessage="Nessuna transazione trovata" />

        </div>
    </div>
</x-admin>