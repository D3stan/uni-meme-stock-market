/**
 * Notification Panel Manager
 * Handles slide-over panel, mark as read, and detail modal
 */
(function() {
    'use strict';

    // DOM Elements
    const overlay = document.getElementById('notification-overlay');
    const panel = document.getElementById('notification-panel');
    const content = document.getElementById('notification-content');
    const loading = document.getElementById('notification-loading');
    const closeBtn = document.getElementById('notification-close-btn');
    const markAllBtn = document.getElementById('mark-all-read-btn');
    
    // Modal elements
    const detailModal = document.getElementById('notification-detail-modal');
    const modalOverlay = document.getElementById('notification-modal-overlay');
    const modalContent = document.getElementById('notification-modal-content');
    const modalClose = document.getElementById('notification-modal-close');
    const modalTitle = document.getElementById('notification-modal-title');
    const modalDate = document.getElementById('notification-modal-date');
    const modalMessage = document.getElementById('notification-modal-message');

    // Badge elements
    const badges = document.querySelectorAll('.notification-badge');

    // CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    let isOpen = false;
    let isModalOpen = false;
    let triggerElement = null;
    let modalTriggerElement = null;

    /**
     * Open the notification panel
     */
    function openPanel() {
        if (isOpen) return;
        isOpen = true;
        triggerElement = document.activeElement;

        // Show overlay
        overlay.classList.remove('pointer-events-none', 'opacity-0');
        overlay.setAttribute('aria-hidden', 'false');

        // Sliding
        panel.classList.remove('translate-x-full');
        panel.setAttribute('aria-hidden', 'false');

        document.body.style.overflow = 'hidden';
        closeBtn.focus();

        loadNotifications();
    }

    /**
     * Close the notification panel
     */
    function closePanel() {
        if (!isOpen) return;
        isOpen = false;

        // To avoid aria-hidden warning
        if (triggerElement && typeof triggerElement.focus === 'function') {
            triggerElement.focus();
        } else {
            document.body.focus();
        }

        // Hide overlay
        overlay.classList.add('pointer-events-none', 'opacity-0');
        overlay.setAttribute('aria-hidden', 'true');

        // Sliding
        panel.classList.add('translate-x-full');
        panel.setAttribute('aria-hidden', 'true');

        document.body.style.overflow = '';
    }

    /**
     * Load notifications via fetch
     */
    async function loadNotifications() {
        if (loading) loading.style.display = 'flex';

        try {
            const response = await fetch('/notifications', {
                headers: {
                    'Accept': 'text/html',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (!response.ok) throw new Error('Failed to load notifications');

            const html = await response.text();
            content.innerHTML = html;

            attachItemListeners();

        } catch (error) {
            console.error('Error loading notifications:', error);
            content.innerHTML = `
                <div class="flex flex-col items-center justify-center h-48 text-center">
                    <span class="material-icons text-4xl text-brand-danger mb-3">error</span>
                    <p class="text-text-muted mb-4">Errore nel caricamento</p>
                    <button type="button" onclick="window.NotificationPanel.load()" 
                            class="px-4 py-2 bg-brand text-text-main rounded-lg text-sm font-medium hover:bg-brand-light transition-colors">
                        Riprova
                    </button>
                </div>
            `;
        }
    }

    /**
     * Attach event listeners to notification items
     */
    function attachItemListeners() {

        document.querySelectorAll('.notification-detail-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = btn.dataset.id;
                openDetailModal(id);
            });
        });

        document.querySelectorAll('.notification-mark-read-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const id = btn.dataset.id;
                markAsRead(id, btn.closest('.notification-item'));
            });
        });
    }

    /**
     * Mark a single notification as read
     */
    async function markAsRead(id, itemElement) {
        try {
            const response = await fetch('/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ id: parseInt(id) }),
            });

            const data = await response.json();

            if (data.success) {
                if (itemElement) {
                    itemElement.classList.remove('bg-surface-200', 'border-brand');
                    itemElement.classList.add('bg-surface-50', 'opacity-70');
                    itemElement.dataset.read = 'true';
                    
                    const markBtn = itemElement.querySelector('.notification-mark-read-btn');
                    if (markBtn) markBtn.remove();
                }

                updateBadge(data.unreadCount);
                loadNotifications();
            }
        } catch (error) {
            console.error('Error marking as read:', error);
        }
    }

    /**
     * Mark all notifications as read
     */
    async function markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            const data = await response.json();

            if (data.success) {
                updateBadge(0);
                loadNotifications();
            }
        } catch (error) {
            console.error('Error marking all as read:', error);
        }
    }

    /**
     * Update badge count
     */
    function updateBadge(count) {
        badges.forEach(badge => {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    }

    /**
     * Open detail modal
     */
    async function openDetailModal(id) {
        try {
            const response = await fetch(`/notifications/${id}`, {
                headers: {
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                modalTitle.textContent = data.notification.title;
                modalDate.textContent = data.notification.created_at;
                modalMessage.textContent = data.notification.message;

                isModalOpen = true;
                modalTriggerElement = document.activeElement;
                detailModal.classList.remove('pointer-events-none', 'opacity-0');
                detailModal.setAttribute('aria-hidden', 'false');
                modalClose.focus();
            }
        } catch (error) {
            console.error('Error loading notification detail:', error);
        }
    }

    /**
     * Close detail modal
     */
    function closeDetailModal() {
        if (!isModalOpen) return;
        isModalOpen = false;

        // Restore focus before hiding to avoid aria-hidden warning
        if (modalTriggerElement && typeof modalTriggerElement.focus === 'function') {
            modalTriggerElement.focus();
        }

        detailModal.classList.add('pointer-events-none', 'opacity-0');
        detailModal.setAttribute('aria-hidden', 'true');
    }

    // Open panel triggers (bell icons)
    document.querySelectorAll('[data-open-notifications]').forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            openPanel();
        });
    });

    // Close panel
    if (closeBtn) closeBtn.addEventListener('click', closePanel);
    if (overlay) overlay.addEventListener('click', closePanel);
    if (markAllBtn) markAllBtn.addEventListener('click', markAllAsRead);
    if (modalClose) modalClose.addEventListener('click', closeDetailModal);
    if (modalOverlay) modalOverlay.addEventListener('click', closeDetailModal);

    // Press esc to close panel
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (isModalOpen) {
                closeDetailModal();
            } else if (isOpen) {
                closePanel();
            }
        }
    });

    // Expose for external use
    window.NotificationPanel = {
        open: openPanel,
        close: closePanel,
        load: loadNotifications,
        updateBadge: updateBadge,
    };

})();
