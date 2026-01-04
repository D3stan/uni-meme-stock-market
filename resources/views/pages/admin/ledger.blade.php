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
            <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
                <a href="{{ route('admin.ledger', ['type' => 'all']) }}">
                    <x-ui.chip :active="$currentType === 'all'">Tutte</x-ui.chip>
                </a>
                <a href="{{ route('admin.ledger', ['type' => 'buy']) }}">
                    <x-ui.chip :active="$currentType === 'buy'">Acquisti</x-ui.chip>
                </a>
                <a href="{{ route('admin.ledger', ['type' => 'sell']) }}">
                    <x-ui.chip :active="$currentType === 'sell'">Vendite</x-ui.chip>
                </a>
                <a href="{{ route('admin.ledger', ['type' => 'bonus']) }}">
                    <x-ui.chip :active="$currentType === 'bonus'">Bonus</x-ui.chip>
                </a>
                <a href="{{ route('admin.ledger', ['type' => 'dividend']) }}">
                    <x-ui.chip :active="$currentType === 'dividend'">Dividendi</x-ui.chip>
                </a>
                <a href="{{ route('admin.ledger', ['type' => 'listing_fee']) }}">
                    <x-ui.chip :active="$currentType === 'listing_fee'">Listing Fee</x-ui.chip>
                </a>
            </div>
        </div>
    </div>
</x-admin>