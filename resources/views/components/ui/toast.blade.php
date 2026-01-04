{{-- Generic Toast Notification Component --}}
@props(['type' => 'success', 'message' => ''])

<div id="toast-container" class="fixed top-20 left-1/2 -translate-x-1/2 z-[80] pointer-events-none">
</div>

{{-- Toast Template (hidden, cloned by JavaScript) --}}
<template id="toast-template">
    <div class="toast pointer-events-auto bg-gray-800 rounded-xl shadow-lg border border-gray-700 p-4 mb-3 
                transform transition-all duration-300 max-w-sm w-full
                flex items-start gap-3">
        <div class="toast-icon flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center">
        </div>
        <div class="flex-1 min-w-0">
            <p class="toast-message text-sm font-medium text-white"></p>
        </div>
        <button class="toast-close flex-shrink-0 text-gray-400 hover:text-white transition-colors">
            <span class="material-icons text-lg">close</span>
        </button>
    </div>
</template>
