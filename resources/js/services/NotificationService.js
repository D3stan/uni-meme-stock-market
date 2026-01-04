/**
 * Notification Service - Manages toast notifications
 */
import EventBus from '../core/events.js';

class NotificationService {
    constructor() {
        this.container = null;
        this.template = null;
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }

    setup() {
        this.container = document.getElementById('toast-container');
        this.template = document.getElementById('toast-template');
    }

    /**
     * Show a toast notification
     */
    show(message, type = 'success', duration = 4000) {
        if (!this.container || !this.template) {
            console.warn('Toast container or template not found');
            return;
        }

        // Clone template
        const toast = this.template.content.cloneNode(true).querySelector('.toast');
        
        // Set icon based on type
        const iconContainer = toast.querySelector('.toast-icon');
        const iconConfig = this.getIconConfig(type);
        iconContainer.className = `toast-icon flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center ${iconConfig.bgClass}`;
        iconContainer.innerHTML = `<span class="material-icons text-lg ${iconConfig.iconClass}">${iconConfig.icon}</span>`;

        // Set message
        toast.querySelector('.toast-message').textContent = message;

        // Add close handler
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.remove(toast);
        });

        // Add to container
        this.container.appendChild(toast);

        // Trigger animation
        requestAnimationFrame(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            requestAnimationFrame(() => {
                toast.style.transition = 'all 0.3s ease-out';
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            });
        });

        // Auto remove after duration
        setTimeout(() => {
            this.remove(toast);
        }, duration);

        // Emit event
        EventBus.emit('toast:shown', { message, type });
    }

    /**
     * Remove a toast
     */
    remove(toast) {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    /**
     * Get icon configuration for toast type
     */
    getIconConfig(type) {
        const configs = {
            success: {
                icon: 'check_circle',
                iconClass: 'text-green-400',
                bgClass: 'bg-green-500/20'
            },
            error: {
                icon: 'error',
                iconClass: 'text-red-400',
                bgClass: 'bg-red-500/20'
            },
            warning: {
                icon: 'warning',
                iconClass: 'text-yellow-400',
                bgClass: 'bg-yellow-500/20'
            },
            info: {
                icon: 'info',
                iconClass: 'text-blue-400',
                bgClass: 'bg-blue-500/20'
            }
        };

        return configs[type] || configs.info;
    }

    // Convenience methods
    success(message, duration) {
        this.show(message, 'success', duration);
    }

    error(message, duration) {
        this.show(message, 'error', duration);
    }

    warning(message, duration) {
        this.show(message, 'warning', duration);
    }

    info(message, duration) {
        this.show(message, 'info', duration);
    }
}

export default new NotificationService();
