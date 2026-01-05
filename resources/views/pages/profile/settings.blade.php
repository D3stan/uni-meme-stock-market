<x-minimal>
    <div class="bg-gray-950 min-h-screen text-white">
        
        {{-- Header with back button --}}
        <header class="sticky top-0 z-10 bg-gray-950/95 backdrop-blur-sm border-b border-gray-900">
            <div class="flex items-center justify-center relative px-4 py-4">
                <a href="{{ route('profile') }}" class="absolute left-4 text-brand hover:text-brand-soft transition-colors flex items-center gap-1">
                    <span class="material-icons">chevron_left</span>
                    <span class="font-medium">Indietro</span>
                </a>
                <h1 class="text-xl font-bold">Impostazioni</h1>
            </div>
        </header>

        <div class="max-w-2xl mx-auto px-4 py-6 pb-20">
            
            <form action="{{ route('profile.settings.update') }}" method="POST" enctype="multipart/form-data" id="settings-form">
                @csrf
                @method('PUT')

                {{-- PROFILO PERSONALE Section --}}
                <section class="mb-8">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
                        PROFILO PERSONALE
                    </h2>
                    
                    <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800">
                        {{-- Profile Picture Upload --}}
                        <div class="mb-6">
                            <x-profile.picture-upload :user="$user" />
                        </div>

                        {{-- Nickname Section --}}
                        <div>
                            <label class="block text-sm font-medium text-brand mb-2">
                                Nickname
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-white text-lg font-medium focus:outline-none focus:border-brand transition-colors"
                                    placeholder="Il tuo nickname"
                                >
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-400">
                                    <span class="material-icons text-xl">edit</span>
                                </button>
                            </div>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Save Button --}}
                        <button 
                            type="submit"
                            class="w-full mt-6 bg-brand hover:bg-brand-dark text-white font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2"
                        >
                            <span class="material-icons text-xl">save</span>
                            <span>Salva Modifiche</span>
                        </button>
                    </div>
                </section>

                {{-- SICUREZZA Section --}}
                <section class="mb-8">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
                        SICUREZZA
                    </h2>
                    
                    <x-profile.settings-button 
                        icon="lock"
                        label="Cambia Password"
                        type="button"
                        onclick="openModal('password-modal')"
                    />
                </section>

                {{-- NOTIFICHE Section --}}
                <section class="mb-8">
                    <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
                        NOTIFICHE
                    </h2>
                    
                    <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 space-y-6">
                        
                        {{-- Dividendi Meme Toggle --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-white font-medium mb-1">Dividendi Meme</h3>
                                <p class="text-gray-500 text-sm">Alert sui pagamenti accademici</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="notify_dividends" 
                                    value="1" 
                                    {{ old('notify_dividends', true) ? 'checked' : '' }}
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>

                        {{-- Comunicazioni Rettorato Toggle (Mandatory) --}}
                        <div class="flex items-center justify-between opacity-60">
                            <div>
                                <h3 class="text-gray-600 font-medium mb-1">Comunicazioni Rettorato</h3>
                                <p class="text-gray-700 text-sm">Obbligatorio per tutti gli studenti</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-not-allowed">
                                <input 
                                    type="checkbox" 
                                    name="notify_rector" 
                                    value="1" 
                                    checked
                                    disabled
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-gray-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-gray-600 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-gray-600 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-gray-700"></div>
                            </label>
                        </div>
                    </div>
                </section>

            </form>

            {{-- ZONA PERICOLOSA Section --}}
            <section class="mb-8">
                <h2 class="text-xs font-semibold text-red-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="material-icons text-base">warning</span>
                    ZONA PERICOLOSA
                </h2>
                
                <div class="space-y-3">
                    {{-- Disattiva Account --}}
                    <x-profile.settings-button 
                        icon="pause_circle"
                        label="Disattiva Account"
                        type="button"
                        onclick="openModal('deactivate-modal')"
                    />

                    {{-- Elimina Account --}}
                    <x-profile.settings-button 
                        icon="delete_forever"
                        label="Elimina Account"
                        type="button"
                        variant="danger"
                        onclick="openModal('delete-modal')"
                    />
                </div>

                <p class="text-gray-600 text-xs text-center mt-4 px-4">
                    L'eliminazione dell'account è irreversibile e rimuoverà tutti i tuoi meme, crediti e cronologia di trading accademico.
                </p>
            </section>

        </div>
    </div>

    {{-- Password Change Modal --}}
    <x-profile.password-modal id="password-modal" />

    {{-- Deactivate Account Confirmation Modal --}}
    <x-ui.confirmation-modal 
        id="deactivate-modal"
        title="Disattiva Account"
        message="Il tuo account verrà temporaneamente sospeso. Potrai riattivarlo in qualsiasi momento effettuando nuovamente il login."
        confirmText="Disattiva"
        confirmClass="bg-gray-700 hover:bg-gray-600"
        :action="route('profile.deactivate')"
        method="POST"
    />

    {{-- Delete Account Confirmation Modal --}}
    <x-ui.confirmation-modal 
        id="delete-modal"
        title="Elimina Account"
        message="Questa azione è irreversibile! Tutti i tuoi dati, meme, crediti e cronologia verranno eliminati permanentemente."
        confirmText="Elimina Definitivamente"
        confirmClass="bg-red-600 hover:bg-red-700"
        :action="route('profile.delete')"
        method="DELETE"
    />

    {{-- Success/Error Toast --}}
    <div id="toast-notification" class="hidden fixed top-4 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full">
        <div id="toast-content" class="bg-gray-900 border rounded-2xl p-4 shadow-lg">
            <div class="flex items-center gap-3">
                <span id="toast-icon" class="material-icons text-2xl"></span>
                <p id="toast-message" class="text-white font-medium flex-1"></p>
                <button onclick="closeToast()" class="text-gray-500 hover:text-gray-300">
                    <span class="material-icons text-xl">close</span>
                </button>
            </div>
        </div>
    </div>

    @push('page-scripts')
    @vite(['resources/js/pages/settings.js'])
    <script type="module">
        import { showToast, closeToast } from '../../js/pages/settings.js';
        
        // Make functions available globally for onclick handlers
        window.showToast = showToast;
        window.closeToast = closeToast;
        
        // Success/error messages from server
        @if(session('success'))
            showToast('check_circle', "{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast('error', "{{ session('error') }}", 'error');
        @endif
    </script>
    @endpush

</x-minimal>
