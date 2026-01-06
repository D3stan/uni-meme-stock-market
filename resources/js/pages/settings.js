/**
 * Settings Page JavaScript
 * Handles form submission, toast notifications, and modal interactions
 */

import NotificationService from '../services/NotificationService.js';

// Define modal functions globally so they're available for inline onclick handlers
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

export function initializeSettings() {
    const settingsForm = document.getElementById('settings-form');
    
    if (settingsForm) {
        settingsForm.addEventListener('submit', handleFormSubmit);
    }
    
    // Make functions globally available
    window.openModal = openModal;
    window.closeModal = closeModal;
}

function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    
    fetch(e.target.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Errore durante il salvataggio');
        }
        return data;
    })
    .then(data => {
        if (data.success) {
            NotificationService.success(data.message || 'Modifiche salvate con successo!');
            // Update avatar if changed
            if (data.user && data.user.avatar) {
                const avatarImg = document.getElementById('avatar-preview');
                if (avatarImg) {
                    avatarImg.src = data.user.avatar;
                }
            }
        } else {
            NotificationService.error(data.message || 'Errore durante il salvataggio');
        }
    })
    .catch(error => {
        console.error('Form submission error:', error);
        NotificationService.error(error.message || 'Si è verificato un errore. Riprova.');
    });
}

// Export modal functions
export { openModal, closeModal };

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeSettings);
} else {
    initializeSettings();
}
