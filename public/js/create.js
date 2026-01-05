document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('uploadMemeForm');
    const tickerInput = document.getElementById('ticker');
    const titleInput = document.getElementById('title');
    const submitBtn = document.getElementById('submitBtn');
    
    let tickerCheckTimeout;
    let tickerValid = false;

    // Real-time ticker validation
    if (tickerInput) {
        tickerInput.addEventListener('input', function() {
            const ticker = this.value.trim().toUpperCase();
            this.value = ticker;

            // Clear previous timeout
            clearTimeout(tickerCheckTimeout);

            // Reset validation state
            tickerInput.classList.remove('border-brand-danger', 'border-brand');
            
            if (ticker.length >= 3) {
                // Check ticker after 500ms delay
                tickerCheckTimeout = setTimeout(() => {
                    checkTickerAvailability(ticker);
                }, 500);
            }
        });
    }

    // Check ticker availability with AJAX
    function checkTickerAvailability(ticker) {
        fetch('/meme/check-ticker', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ ticker })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                tickerInput.classList.add('border-brand-danger');
                tickerInput.classList.remove('border-brand');
                showTickerError('Ticker già esistente');
                tickerValid = false;
            } else {
                tickerInput.classList.add('border-brand');
                tickerInput.classList.remove('border-brand-danger');
                hideTickerError();
                tickerValid = true;
            }
        })
        .catch(error => {
            console.error('Error checking ticker:', error);
        });
    }

    function showTickerError(message) {
        let errorEl = document.getElementById('ticker-error');
        if (!errorEl) {
            errorEl = document.createElement('p');
            errorEl.id = 'ticker-error';
            errorEl.className = 'mt-1 text-xs text-brand-danger';
            tickerInput.parentElement.parentElement.appendChild(errorEl);
        }
        errorEl.textContent = message;
    }

    function hideTickerError() {
        const errorTick = document.getElementById('ticker-error');
        if (errorTick) {
            errorTick.remove();
        }
    }

    // Form submission validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        let errors = [];

        // Get actual image input
        const actualImageInput = document.querySelector('input[name="image"]');
        
        // Validate image
        if (!actualImageInput || !actualImageInput.files || actualImageInput.files.length === 0) {
            errors.push('Devi caricare un\'immagine');
        } else {
            const file = actualImageInput.files[0];
            const maxSize = 10 * 1024 * 1024;
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif', 'image/heic'];
            
            if (file.size > maxSize) {
                errors.push('L\'immagine non può superare 10MB');
            }
            
            if (!allowedTypes.includes(file.type)) {
                errors.push('Formato immagine non supportato (solo JPG, PNG, WEBP, GIF e HEIC)');
            }
        }

        // Validate title
        const title = titleInput.value.trim();
        if (title.length < 3) {
            errors.push('Il titolo deve essere di almeno 3 caratteri');
        }

        // Validate ticker
        const ticker = tickerInput.value.trim();
        if (ticker.length < 3) {
            errors.push('Il ticker deve essere di almeno 3 caratteri');
        } else if (!tickerValid) {
            errors.push('Il ticker non è valido o è già in uso');
        }

        // Show errors or submit
        if (errors.length > 0) {
            showErrors(errors);
            return;
        }

        // Disable submit button and show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="material-icons animate-spin text-lg">refresh</span> Caricamento...';

        // Submit form
        form.submit();
    });

    function showErrors(errors) {
        const errorHtml = `<ul class="list-disc list-inside text-left space-y-1">${errors.map(err => `<li>${err}</li>`).join('')}</ul>`;
        
        // Show error modal or alert
        if (typeof showNotificationModal === 'function') {
            showNotificationModal('error', 'Errori di validazione', errorHtml);
        } else {
            alert('Errori:\n' + errors.join('\n'));
        }
    }
});
