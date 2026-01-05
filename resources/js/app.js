import './bootstrap';

import { initOtpVerification } from './pages/otp-verification.js';
import { initializeSettings } from './pages/settings.js';
import { initMarketplace } from './pages/marketplace.js';

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    if (body.dataset.page === 'otp-verification') {
        initOtpVerification();
    }
    
    if (body.dataset.page === 'settings') {
        initializeSettings();
    }

    // Initialize Marketplace (using querySelector inside the function)
    initMarketplace();
});
