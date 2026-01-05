@props([
    'id' => 'password-modal',
])

{{-- Modal backdrop --}}
<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-surface-50/80 backdrop-blur-sm">
    {{-- Modal container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal content --}}
        <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-surface-100 shadow-xl rounded-3xl border border-surface-200">
            
            {{-- Close button --}}
            <button type="button" onclick="closeModal('{{ $id }}')" class="absolute top-4 right-4 text-text-muted hover:text-text-main transition-colors">
                <span class="material-icons text-2xl">close</span>
            </button>

            {{-- Icon --}}
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-surface-200 rounded-full flex items-center justify-center">
                    <span class="material-icons text-text-main text-3xl">lock</span>
                </div>
            </div>

            {{-- Title --}}
            <h3 class="text-xl font-bold text-text-main text-center mb-6">
                Cambia Password
            </h3>

            {{-- Form --}}
            <form action="{{ route('profile.update-password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Current Password --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-text-muted mb-2">
                        Password Attuale
                    </label>
                    <x-forms.input 
                        type="password" 
                        id="current_password" 
                        name="current_password" 
                        placeholder="Inserisci password attuale"
                        required
                    />
                </div>

                <!-- New Password -->
                <div>
                    <label for="new_password" class="block text-sm font-medium text-text-muted mb-2">
                        Nuova Password
                    </label>
                    <x-forms.input 
                        type="password" 
                        id="new_password" 
                        name="new_password" 
                        placeholder="Inserisci nuova password"
                        required
                    />
                    <p class="text-xs text-text-muted mt-1">Minimo 8 caratteri</p>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-text-muted mb-2">
                        Conferma Nuova Password
                    </label>
                    <x-forms.input 
                        type="password" 
                        id="new_password_confirmation" 
                        name="new_password_confirmation" 
                        placeholder="Conferma nuova password"
                        required
                    />
                </div>

                {{-- Error display --}}
                <div id="password-error" class="hidden text-brand-danger text-sm text-center p-3 bg-brand-danger/20 rounded-lg">
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeModal('{{ $id }}')" class="flex-1 px-4 py-3 text-text-main bg-surface-200 border border-surface-200 hover:bg-surface-200/80 rounded-xl font-medium transition-colors">
                        Annulla
                    </button>
                    <button type="submit" class="btn-primary flex-1">
                        Conferma
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
