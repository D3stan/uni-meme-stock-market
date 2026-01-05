import './bootstrap';

import { initOtpVerification } from './pages/otp-verification.js';

document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;

    if (body.dataset.page === 'otp-verification') {
        initOtpVerification();
    }
});
