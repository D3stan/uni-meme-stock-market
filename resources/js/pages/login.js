/**
 * Login Page
 * Handles forgot password functionality
 */

export function initLogin() {
    const forgotPasswordLink = document.getElementById('forgot-password-link');
    const emailInput = document.getElementById('email');
    const errorDiv = document.getElementById('email-forgot-error');

    if (!forgotPasswordLink || !emailInput || !errorDiv) {
        return;
    }

    forgotPasswordLink.addEventListener('click', function(e) {
        e.preventDefault();
        const email = emailInput.value.trim();
        
        if (!email) {
            errorDiv.classList.remove('hidden');
            emailInput.focus();
            emailInput.classList.add('border-brand-danger');
            return;
        }
        
        // Hide error and reset border
        errorDiv.classList.add('hidden');
        emailInput.classList.remove('border-brand-danger');
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        // Send forgot password request
        fetch('/forgot-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to OTP page with email
                window.location.href = '/verify-otp';
            } else {
                errorDiv.textContent = data.message || 'Errore durante l\'invio dell\'email.';
                errorDiv.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            errorDiv.textContent = 'Errore durante l\'invio della richiesta.';
            errorDiv.classList.remove('hidden');
        });
    });

    // Hide error when user starts typing
    emailInput.addEventListener('input', function() {
        errorDiv.classList.add('hidden');
        emailInput.classList.remove('border-brand-danger');
    });
}
