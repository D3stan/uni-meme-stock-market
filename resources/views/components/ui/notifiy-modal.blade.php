@props([
    'id' => 'notificationModal',
])

<x-ui.modal :id="$id" :show="false" maxWidth="sm">
    <div class="p-8">
        {{-- Close button --}}
        <button aria-label="Chiudi" type="button" id="{{ $id }}-close" class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors">
            <span aria-hidden="true" class="material-icons">close</span>
        </button>

        {{-- Icon --}}
        <div class="flex justify-center pb-4">
            <div id="{{ $id }}-icon-container" class="w-16 h-16 rounded-full flex items-center justify-center">
                <span id="{{ $id }}-icon" class="material-icons text-4xl"></span>
            </div>
        </div>

        {{-- Content --}}
        <div class="text-center">
            <h3 id="{{ $id }}-title" class="text-xl font-bold text-white mb-3"></h3>
            <div id="{{ $id }}-message" class="text-sm text-gray-300"></div>
        </div>

        {{-- Actions --}}
        <div class="pt-6 flex gap-3">
            <button type="button" id="{{ $id }}-action" class="flex-1 px-4 py-3 rounded-lg font-medium transition-colors">OK</button>
        </div>
    </div>
</x-ui.modal>

@push('page-scripts')
<script>
    function showNotificationModal(type, title, message) {
        const modal = document.getElementById('{{ $id }}');
        const iconContainer = document.getElementById('{{ $id }}-icon-container');
        const icon = document.getElementById('{{ $id }}-icon');
        const titleEl = document.getElementById('{{ $id }}-title');
        const messageEl = document.getElementById('{{ $id }}-message');
        const actionBtn = document.getElementById('{{ $id }}-action');
        const closeBtn = document.getElementById('{{ $id }}-close');

        // Set content
        titleEl.textContent = title;
        messageEl.innerHTML = message;

        // Set styling based on type
        if (type === 'success') {
            iconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center bg-green-500/20';
            icon.className = 'material-icons text-4xl text-green-500';
            icon.textContent = 'check_circle';
            actionBtn.className = 'flex-1 px-4 py-3 rounded-lg font-medium transition-colors bg-green-600 hover:bg-green-700 text-white';
        } else if (type === 'error') {
            iconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center bg-red-500/20';
            icon.className = 'material-icons text-4xl text-red-500';
            icon.textContent = 'error';
            actionBtn.className = 'flex-1 px-4 py-3 rounded-lg font-medium transition-colors bg-red-600 hover:bg-red-700 text-white';
        } else if (type === 'warning') {
            iconContainer.className = 'w-16 h-16 rounded-full flex items-center justify-center bg-yellow-500/20';
            icon.className = 'material-icons text-4xl text-yellow-500';
            icon.textContent = 'warning';
            actionBtn.className = 'flex-1 px-4 py-3 rounded-lg font-medium transition-colors bg-yellow-600 hover:bg-yellow-700 text-white';
        }

        // Show modal using global helper
        showModal('{{ $id }}');

        // Close handlers
        const closeModal = () => hideModal('{{ $id }}');
        actionBtn.onclick = closeModal;
        closeBtn.onclick = closeModal;
    }

    // Auto-show modal from session flash messages
    @if(session('success'))
        showNotificationModal('success', 'Successo', '{{ session('success') }}');
    @endif

    @if(session('error'))
        showNotificationModal('error', 'Errore', '{{ session('error') }}');
    @endif
</script>
@endpush
