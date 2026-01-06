import './bootstrap';

import { initOtpVerification } from './pages/otp-verification.js';
import { initializeSettings } from './pages/settings.js';
import { initMarketplace } from './pages/marketplace.js';
import { initLogin } from './pages/login.js';

document.addEventListener('DOMContentLoaded', () => {
    // Check for data-page attribute on body or any child element
    const pageElement = document.querySelector('[data-page]');
    const page = pageElement?.dataset.page || document.body.dataset.page;

    if (page === 'otp-verification') {
        initOtpVerification();
    }
    
    if (page === 'settings') {
        initializeSettings();
    }

    if (page === 'login') {
        initLogin();
    }

    // Initialize Marketplace (using querySelector inside the function)
    initMarketplace();
});
