<x-admin title="Rettorato Admin" label="Torna al Marketplace" icon="home" href="{{ route('market') }}">
    {{-- Resoconto Generale --}}
    <div class="mb-8">
        
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Totale Utenti --}}
            <x-ui.trend-card title="Utenti totali" :value="$totalUsers" :variation="($userVariation >= 0 ? '+' : '') . $userVariation"/>
            {{-- Totale Meme --}}
            <x-ui.trend-card title="Meme totali" :value="$totalMeme" :variation="($memeVariation >= 0 ? '+' : '') . $memeVariation"/>
            {{-- Totale Fee --}}
            <x-ui.trend-card title="Totale Fee" value="€{{ $totalFees }}" :variation="($feeVariation >= 0 ? '+' : '') . $feeVariation"/>
            </section>
    </div>

    {{-- Menu Operativo --}}
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-text-main mb-6">Menu Operativo</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Approva Meme --}}
            <x-ui.options-card href="{{ route('admin.moderation') }}" icon="check_circle" title="Approva Meme" description="Revisione coda"/>

            {{-- Gestione Notifiche --}}
            <x-ui.options-card href="{{ route('admin.notifications') }}" icon="campaign" title="Gestione Notifiche" description="Comunicazioni"/>

            {{-- Gestisci Eventi --}}
            <x-ui.options-card href="{{ route('admin.events') }}" icon="event" title="Gestisci Eventi" description="Calendario & IPO"/>

            {{-- Visione Transazioni --}}
            <x-ui.options-card href="{{ route('admin.ledger') }}" icon="receipt_long" title="Visione Transazioni" description="Ledger di mercato"/>
        </div>
    </div>
</x-admin>