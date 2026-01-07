@props([
    'id' => 'confirmation-modal',
    'title' => 'Conferma Azione',
    'message' => 'Sei sicuro di voler procedere?',
    'confirmText' => 'Conferma',
    'cancelText' => 'Annulla',
    'confirmClass' => 'bg-brand-danger hover:bg-brand-danger-dark',
    'action' => '#',
    'method' => 'POST',
])

{{-- Modal backdrop --}}
<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-surface-50/80 backdrop-blur-sm">
    {{-- Modal container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal content --}}
        <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-surface-100 shadow-xl rounded-3xl border border-surface-200">
            
            {{-- Close button --}}
            <button type="button" onclick="closeModal('{{ $id }}')" aria-label="Chiudi" class="absolute top-4 right-4 text-text-muted hover:text-text-main transition-colors">
                <span class="material-icons text-2xl" aria-hidden="true">close</span>
            </button>

            {{-- Icon --}}
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-brand-danger/20 rounded-full flex items-center justify-center">
                    <span class="material-icons text-brand-danger text-3xl" aria-hidden="true">warning</span>
                </div>
            </div>

            {{-- Title --}}
            <h3 class="text-xl font-bold text-text-main text-center mb-3">
                {{ $title }}
            </h3>

            {{-- Message --}}
            <p class="text-text-muted text-center text-sm mb-6">
                {{ $message }}
            </p>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('{{ $id }}')" class="flex-1 px-4 py-3 text-text-main bg-surface-200 border border-surface-200 hover:bg-surface-200/80 rounded-xl font-medium transition-colors">
                    {{ $cancelText }}
                </button>
                
                <form action="{{ $action }}" method="POST" class="flex-1">
                    @csrf
                    @if($method !== 'POST')
                        @method($method)
                    @endif
                    <button type="submit" class="w-full px-4 py-3 text-text-main {{ $confirmClass }} rounded-xl font-medium transition-colors">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
