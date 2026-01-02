<x-app-layout>
    <main class="max-w-2xl mx-auto space-y-12 my-6">
        
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
                        placeholder="nome.cognome@studio.unibo.it" 
                    />
                </div>
                
                {{-- Input con valore --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-white">Password</label>
                    <x-forms.input 
                        name="username" 
                        type="password" 
                        placeholder="Password"
                    />
                </div>
                
                {{-- Input disabilitato --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-white">CFU (Disabilitato)</label>
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
                {{-- Varianti --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Varianti</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-forms.button variant="primary">Primary</x-forms.button>
                        <x-forms.button variant="secondary">Secondary</x-forms.button>
                        <x-forms.button variant="success">Success</x-forms.button>
                        <x-forms.button variant="danger">Danger</x-forms.button>
                        <x-forms.button variant="outline">Outline</x-forms.button>
                    </div>
                </div>

                {{-- Dimensioni --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Dimensioni</h3>
                    <div class="flex flex-wrap items-center gap-3">
                        <x-forms.button size="sm">Small</x-forms.button>
                        <x-forms.button size="md">Medium</x-forms.button>
                        <x-forms.button size="lg">Large</x-forms.button>
                    </div>
                </div>

                {{-- Stati --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Stati</h3>
                    <div class="flex flex-wrap gap-3">
                        <x-forms.button>Attivo</x-forms.button>
                        <x-forms.button disabled>Disabilitato</x-forms.button>
                    </div>
                </div>

                {{-- Esempio reale: Trading --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Esempio Trading</h3>
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
    </main>
</x-app-layout>