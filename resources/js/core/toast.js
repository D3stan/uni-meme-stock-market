/**
 * Toast Initialization
 * Handles session-based toast notifications
 */
(function() {
    'use strict';

    /**
     * Initialize toast from data attribute
     */
    function initToast() {
        const toastData = document.body.dataset.toast;
        
        if (!toastData) return;

        try {
            const toast = JSON.parse(toastData);
            
            // Dynamically import NotificationService
            import('/resources/js/services/NotificationService.js')
                .then(module => {
                    const NotificationService = module.default;
                    NotificationService.show(toast.message, toast.type || 'info');
                })
                .catch(error => {
                    console.error('Errore caricamento NotificationService:', error);
                });

            // Clean up the data attribute after use
            delete document.body.dataset.toast;
        } catch (error) {
            console.error('Errore parsing dati toast:', error);
        }
    }

    // Initialize on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initToast);
    } else {
        initToast();
    }

})();
