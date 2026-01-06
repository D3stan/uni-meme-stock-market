import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/bootstrap.js',
                'resources/js/core/notifications.js',
                'resources/js/core/toast.js',
                'resources/js/core/events.js',
                'resources/js/core/api.js',
                'resources/js/pages/trading.js',
                'resources/js/pages/portfolio.js',
                'resources/js/pages/login.js',
                'resources/js/pages/marketplace.js',
                'resources/js/pages/otp-verification.js',
                'resources/js/pages/settings.js',
                'resources/js/pages/trading.js',
                'resources/js/pages/create.js',
                'resources/js/pages/admin/moderation.js',
                'resources/js/pages/admin/events.js',
                'resources/js/services/NotificationService.js',
                'resources/js/services/PriceUpdateService.js',
                'resources/js/services/TradingService.js',
                'resources/js/utils/debounce.js',
                'resources/js/utils/format.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
