/**
 * Settings Page JavaScript
 * Handles form submission, toast notifications, and modal interactions
 */

export function initializeSettings() {
    const settingsForm = document.getElementById('settings-form');
    
    if (settingsForm) {
        settingsForm.addEventListener('submit', handleFormSubmit);
    }
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
            showToast('check_circle', data.message || 'Modifiche salvate con successo!', 'success');
            // Update avatar if changed
            if (data.user && data.user.avatar) {
                const avatarImg = document.getElementById('avatar-preview');
                if (avatarImg) {
                    avatarImg.src = data.user.avatar;
                }
            }
        } else {
            showToast('error', data.message || 'Errore durante il salvataggio', 'error');
        }
    })
    .catch(error => {
        console.error('Form submission error:', error);
        showToast('error', error.message || 'Si è verificato un errore. Riprova.', 'error');
    });
}

export function showToast(icon, message, type) {
    const toast = document.getElementById('toast-notification');
    const toastIcon = document.getElementById('toast-icon');
    const toastMessage = document.getElementById('toast-message');
    const toastContent = document.getElementById('toast-content');
    
    if (!toast || !toastIcon || !toastMessage || !toastContent) {
        console.error('Toast elements not found');
        return;
    }
    
    toastIcon.textContent = icon;
    toastMessage.textContent = message;
    
    // Set colors based on type
    if (type === 'success') {
        toastIcon.className = 'material-icons text-2xl text-green-500';
        toastContent.className = 'bg-gray-900 border border-green-900 rounded-2xl p-4 shadow-lg';
    } else {
        toastIcon.className = 'material-icons text-2xl text-red-500';
        toastContent.className = 'bg-gray-900 border border-red-900 rounded-2xl p-4 shadow-lg';
    }
    
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        closeToast();
    }, 4000);
}

export function closeToast() {
    const toast = document.getElementById('toast-notification');
    if (toast) {
        toast.classList.add('hidden');
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeSettings);
} else {
    initializeSettings();
}
