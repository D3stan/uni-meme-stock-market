@props([
    'id' => 'modal',
    'show' => false,
    'maxWidth' => 'md'
])

@php
$maxWidthClasses = match($maxWidth) {
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    default => 'max-w-md',
};
@endphp

<div 
    id="{{ $id }}"
    class="modal fixed inset-0 z-50 overflow-y-auto {{ $show ? '' : 'hidden' }}"
    aria-labelledby="{{ $id }}-title"
    aria-modal="true"
    role="dialog"
>
    <!-- Backdrop -->
    <div 
        class="modal-backdrop fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity duration-300"
        onclick="document.getElementById('{{ $id }}').classList.add('hidden')"
    ></div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div 
            class="modal-content relative w-full {{ $maxWidthClasses }} bg-gray-900 rounded-3xl shadow-2xl transform transition-all duration-300"
            onclick="event.stopPropagation()"
        >
            {{ $slot }}
        </div>
    </div>
</div>

@push('page-scripts')
<script>
(function() {
    const modal = document.getElementById('{{ $id }}');
    if (!modal) return;

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    });

    // Prevent body scroll when modal is open
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                if (modal.classList.contains('hidden')) {
                    document.body.style.overflow = '';
                } else {
                    document.body.style.overflow = 'hidden';
                }
            }
        });
    });
    observer.observe(modal, { attributes: true });
})();

// Global helper functions
window.showModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
    }
};

window.hideModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
    }
};
</script>
@endpush
