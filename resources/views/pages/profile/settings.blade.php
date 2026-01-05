<x-minimal>
    <div class="bg-surface-50 min-h-screen text-text-main" data-page="settings">
        
        {{-- Header with back button --}}
        <header class="sticky top-0 z-10 bg-surface-50/95 backdrop-blur-sm border-b border-surface-200">
            <div class="flex items-center justify-center relative px-4 py-4">
                <a href="{{ route('profile') }}" class="absolute left-4 text-brand hover:text-brand-light transition-colors flex items-center gap-1">
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
                    <h2 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-4">
                        PROFILO PERSONALE
                    </h2>
                    
                    <div class="card-base p-6">
                        {{-- Profile Picture Upload --}}
                        <div class="mb-6">
                            <x-profile.picture-upload :user="$user" />
                        </div>

                        {{-- Nickname Section --}}
                        <div>
                            <label class="block text-sm font-medium text-text-muted mb-2">
                                Nickname
                            </label>
                            <div class="relative">
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}"
                                    class="input-base text-lg font-medium"
                                    placeholder="Il tuo nickname"
                                >
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-text-muted/60 hover:text-text-muted">
                                    <span class="material-icons text-xl">edit</span>
                                </button>
                            </div>
                            @error('name')
                                <p class="text-brand-danger text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Save Button --}}
                        <button 
                            type="submit"
                            class="btn-primary w-full mt-6 flex items-center justify-center gap-2"
                        >
                            <span class="material-icons text-xl">save</span>
                            <span>Salva Modifiche</span>
                        </button>
                    </div>
                </section>

                {{-- SICUREZZA Section --}}
                <section class="mb-8">
                    <h2 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-4">
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
                    <h2 class="text-xs font-semibold text-text-muted uppercase tracking-wider mb-4">
                        NOTIFICHE
                    </h2>
                    
                    <div class="card-base p-6 space-y-6">
                        
                        {{-- Dividendi Meme Toggle --}}
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-text-main font-medium mb-1">Dividendi Meme</h3>
                                <p class="text-text-muted text-sm">Alert sui pagamenti accademici</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="notify_dividends" 
                                    value="1" 
                                    {{ old('notify_dividends', true) ? 'checked' : '' }}
                                    class="sr-only peer"
                                >
                                <div class="w-11 h-6 bg-surface-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-brand/30 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-text-main after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-text-main after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand"></div>
                            </label>
                        </div>

                        {{-- Comunicazioni Rettorato Toggle (Mandatory) --}}
                        <div class="flex items-center justify-between opacity-60">
                            <div>
                                <h3 class="text-text-muted font-medium mb-1">Comunicazioni Rettorato</h3>
                                <p class="text-text-muted text-sm">Obbligatorio per tutti gli studenti</p>
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
                                <div class="w-11 h-6 bg-surface-200 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-text-muted/60 after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-text-muted/60 after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-surface-200/50"></div>
                            </label>
                        </div>
                    </div>
                </section>

            </form>

            {{-- ZONA PERICOLOSA Section --}}
            <section class="mb-8">
                <h2 class="text-xs font-semibold text-brand-danger uppercase tracking-wider mb-4 flex items-center gap-2">
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

                <p class="text-text-muted text-xs text-center mt-4 px-4">
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
        confirmClass="bg-surface-200 hover:bg-surface-200/80"
        :action="route('profile.deactivate')"
        method="POST"
    />

    {{-- Delete Account Confirmation Modal --}}
    <x-ui.confirmation-modal 
        id="delete-modal"
        title="Elimina Account"
        message="Questa azione è irreversibile! Tutti i tuoi dati, meme, crediti e cronologia verranno eliminati permanentemente."
        confirmText="Elimina Definitivamente"
        confirmClass="bg-brand-danger hover:bg-brand-danger-dark"
        :action="route('profile.delete')"
        method="DELETE"
    />

    {{-- Success/Error Toast --}}
    <div id="toast-notification" class="hidden fixed top-4 left-1/2 -translate-x-1/2 z-50 max-w-sm w-full">
        <div id="toast-content" class="card-base p-4">
            <div class="flex items-center gap-3">
                <span id="toast-icon" class="material-icons text-2xl"></span>
                <p id="toast-message" class="text-text-main font-medium flex-1"></p>
                <button onclick="closeToast()" class="text-text-muted hover:text-text-main">
                    <span class="material-icons text-xl">close</span>
                </button>
            </div>
        </div>
    </div>

    @push('page-scripts')
    <script>
        // Success/error messages from server
        @if(session('success'))
            if (typeof window.showToast === 'function') {
                window.showToast('check_circle', "{{ session('success') }}", 'success');
            }
        @endif

        @if(session('error'))
            if (typeof window.showToast === 'function') {
                window.showToast('error', "{{ session('error') }}", 'error');
            }
        @endif
    </script>
    @endpush

</x-minimal>
