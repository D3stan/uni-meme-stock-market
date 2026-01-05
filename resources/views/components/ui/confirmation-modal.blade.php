@props([
    'id' => 'confirmation-modal',
    'title' => 'Conferma Azione',
    'message' => 'Sei sicuro di voler procedere?',
    'confirmText' => 'Conferma',
    'cancelText' => 'Annulla',
    'confirmClass' => 'bg-red-600 hover:bg-red-700',
    'action' => '#',
    'method' => 'POST',
])

{{-- Modal backdrop --}}
<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto bg-gray-900/80 backdrop-blur-sm">
    {{-- Modal container --}}
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Modal content --}}
        <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-gray-900 shadow-xl rounded-3xl border border-gray-800">
            
            {{-- Close button --}}
            <button type="button" onclick="closeModal('{{ $id }}')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-300 transition-colors">
                <span class="material-icons text-2xl">close</span>
            </button>

            {{-- Icon --}}
            <div class="flex justify-center mb-4">
                <div class="w-16 h-16 bg-red-900/20 rounded-full flex items-center justify-center">
                    <span class="material-icons text-red-500 text-3xl">warning</span>
                </div>
            </div>

            {{-- Title --}}
            <h3 class="text-xl font-bold text-white text-center mb-3">
                {{ $title }}
            </h3>

            {{-- Message --}}
            <p class="text-gray-400 text-center text-sm mb-6">
                {{ $message }}
            </p>

            {{-- Actions --}}
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('{{ $id }}')" class="flex-1 px-4 py-3 text-white bg-gray-800 border border-gray-700 hover:bg-gray-700 rounded-xl font-medium transition-colors">
                    {{ $cancelText }}
                </button>
                
                <form action="{{ $action }}" method="POST" class="flex-1">
                    @csrf
                    @if($method !== 'POST')
                        @method($method)
                    @endif
                    <button type="submit" class="w-full px-4 py-3 text-white {{ $confirmClass }} rounded-xl font-medium transition-colors">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
