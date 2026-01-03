<x-base>
    <main class="max-w-2xl mx-4 md:mx-auto space-y-12 my-6">
        
        {{-- Test Input Component --}}
        <section>
            <h1 class="text-2xl font-bold text-white mb-6">Test Input Component</h1>
            
            <div class="space-y-6">
                {{-- Input base --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-white">Email</label>
                    <x-forms.input 
                        name="email" 
                        type="email" 
                        icon="school" 
                        placeholder="nome.cognome@studio.unibo.it" 
                    />
                </div>
                
                {{-- Input con valore --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-white">Password</label>
                    <x-forms.input 
                        name="username" 
                        icon="lock" 
                        type="password" 
                        placeholder="Password"
                    />
                </div>
                
                {{-- Input disabled--}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-white">CFU (Disabled)</label>
                    <x-forms.input 
                        name="cfu" 
                        value="1000" 
                        disabled 
                    />
                </div>
            </div>
        </section>

        {{-- Test Button Component --}}
        <section>
            <h1 class="text-2xl font-bold text-white mb-6">Test Button Component</h1>
            
            <div class="space-y-6">
                {{-- Variants --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Variants</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-forms.button variant="primary">Primary</x-forms.button>
                        <x-forms.button variant="secondary">Secondary</x-forms.button>
                        <x-forms.button variant="success">Success</x-forms.button>
                        <x-forms.button variant="danger">Danger</x-forms.button>
                        <x-forms.button variant="outline">Outline</x-forms.button>
                    </div>
                </div>

                {{-- Size --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Size</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <x-forms.button size="sm">Small</x-forms.button>
                        <x-forms.button size="md">Medium</x-forms.button>
                        <x-forms.button size="lg">Large</x-forms.button>
                    </div>
                </div>

                {{-- Status --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Status</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-forms.button>Attivo</x-forms.button>
                        <x-forms.button disabled>Disabilitato</x-forms.button>
                    </div>
                </div>

                {{-- Example: Trading --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Trading</h3>
                    <div class="flex gap-3">
                        <x-forms.button variant="success" class="flex-1">
                            Compra $DOGE
                        </x-forms.button>
                        <x-forms.button variant="danger" class="flex-1">
                            Vendi $DOGE
                        </x-forms.button>
                    </div>
                </div>
            </div>
        </section>

        {{-- Test Badge Change Component --}}
        <section>
            <h1 class="text-2xl font-bold text-white mb-6">Test Badge Price Variation</h1>
            
            <div class="space-y-6">
                {{-- Variations positive/negative/neutral --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Variations</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-ui.badge-change :value="5.43" />
                        <x-ui.badge-change :value="0.00" />
                        <x-ui.badge-change :value="-8.92" />
                    </div>
                </div>

                {{-- Size --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Size</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <x-ui.badge-change :value="3.5" size="sm" />
                        <x-ui.badge-change :value="3.5" size="md" />
                        <x-ui.badge-change :value="3.5" size="lg" />
                    </div>
                </div>

                {{-- With/without icon --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">With/without Icon</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-ui.badge-change :value="5.43" :showIcon="true" />
                        <x-ui.badge-change :value="-2.18" :showIcon="false" />
                    </div>
                </div>
            </div>
        </section>

        {{-- Test Meme Card Components --}}
        <section>
            <h1 class="text-2xl font-bold text-white mb-6">Test Meme Card Components</h1>
            
            <div class="space-y-8">
                {{-- Compact Card (per Landing/Profilo) --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Compact Card(Landing/Profile)</h3>
                    <div class="space-y-3">
                        <x-meme.card-compact 
                            name="Meme Everywhere"
                            image="storage/test/meme.webp" 
                            ticker="MEVR"
                            :price="42.50"
                            :change="15.8"
                        />
                        
                        <x-meme.card-compact 
                            name="Un segreto è un segreto"
                            image="storage/test/meme.jpeg" 
                            ticker="SCRT"
                            :price="127.30"
                            :change="-3.2"
                        />
                    </div>
                </div>

                {{-- Extended Card (Marketplace) --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Extended Card (Marketplace)</h3>
                    <div class="space-y-4">
                        <x-meme.card 
                            name="Un segreto è un segreto"
                            image="storage/test/meme.jpeg" 
                            ticker="SCRT"
                            :price="42.50"
                            :change="15.8"
                            creatorName="Mario Rossi"
                            status="new"
                            tradeUrl="#"
                        />
                        
                        <x-meme.card 
                            name="Meme Everywhere"
                            image="storage/test/meme.webp" 
                            ticker="MEVR"
                            :price="127.30"
                            :change="-3.2"
                            creatorName="Luigi Verdi"
                            status="pending"
                            tradeUrl="#"
                        />
                    </div>
                </div>
            </div>
        </section>
    </main>
</x-base>