<x-admin>
    <div class="min-h-screen pb-8 pt-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex items-center justify-center mb-8">
                <h1 class="text-3xl font-bold text-white">Rettorato Admin</h1>
            </div>

            {{-- Resoconto Generale --}}
            <div class="mb-8">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    {{-- Totale Utenti --}}
                    <x-ui.trend-card title="Utenti totali" value="12" variation="+4"/>

                    {{-- Totale Fee --}}
                    <x-ui.trend-card title="Totale Fee" value="€12,450" variation="+6.20"/>

                    {{-- Inflazione --}}
                    <x-ui.trend-card title="Inflazione" value="2.1%" variation="-0.50"/>
                </div>
            </div>

            {{-- Menu Operativo --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white mb-6">Menu Operativo</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Approva Meme --}}
                    <x-ui.options-card href="#" icon="check_circle" title="Approva Meme" description="Revisione coda"/>

                    {{-- Gestione Notifiche --}}
                    <x-ui.options-card href="#" icon="campaign" title="Gestione Notifiche" description="Comunicazioni"/>

                    {{-- Gestisci Eventi --}}
                    <x-ui.options-card href="#" icon="event" title="Gestisci Eventi" description="Calendario & IPO"/>

                    {{-- Visione Transazioni --}}
                    <x-ui.options-card href="#" icon="receipt_long" title="Visione Transazioni" description="Ledger di mercato"/>
                </div>
            </div>
        </div>
    </div>
</x-admin>