/**
 * Order Modal Component - Handles buy/sell order flow with slippage protection
 * Implements the UML flow from tradingCore.wsd
 */
import TradingService from '../services/TradingService.js';
import NotificationService from '../services/NotificationService.js';
import EventBus from '../core/events.js';
import { formatCFU, formatNumber } from '../utils/format.js';
import { debounce } from '../utils/debounce.js';

class OrderModal {
    constructor(tradingData) {
        this.data = tradingData;
        this.currentType = 'buy'; // 'buy' or 'sell'
        this.currentPreview = null;
        this.isOpen = false;
        
        this.initElements();
        this.attachEventListeners();
    }

    initElements() {
        // Modal elements
        this.modal = document.getElementById('order-modal');
        this.backdrop = document.getElementById('order-modal-backdrop');
        this.modalTitle = document.getElementById('modal-title');
        this.quantityInput = document.getElementById('quantity-input');
        this.confirmBtn = document.getElementById('btn-confirm-order');
        this.btnSpinner = document.getElementById('btn-spinner');
        this.btnText = document.getElementById('btn-text');
        
        // Debug: Controlla se elementi critici esistono
        if (!this.modal) console.error('OrderModal: elemento order-modal non trovato');
        if (!this.backdrop) console.error('OrderModal: elemento order-modal-backdrop non trovato');
        
        // Display elements
        this.userBalance = document.getElementById('user-balance');
        this.holdingsInfo = document.getElementById('holdings-info');
        this.holdingsQuantity = document.getElementById('user-holdings-quantity');
        this.costSubtotal = document.getElementById('cost-subtotal');
        this.costFee = document.getElementById('cost-fee');
        this.costTotal = document.getElementById('cost-total');
        
        // Accordion
        this.accordionToggle = document.getElementById('cost-accordion-toggle');
        this.accordionContent = document.getElementById('cost-accordion-content');
        this.accordionIcon = document.getElementById('accordion-icon');
        
        // Loading
        this.loadingState = document.getElementById('modal-loading');
        
        // Slippage modal
        this.slippageModal = document.getElementById('slippage-modal');
        this.slippageBackdrop = document.getElementById('slippage-modal-backdrop');
        this.slippageExpected = document.getElementById('slippage-expected');
        this.slippageActual = document.getElementById('slippage-actual');
        this.slippageChange = document.getElementById('slippage-change');
        this.closeSlippageBtn = document.getElementById('btn-close-slippage');
    }

    attachEventListeners() {
        // Open modal buttons
        document.getElementById('btn-buy')?.addEventListener('click', () => this.open('buy'));
        document.getElementById('btn-sell')?.addEventListener('click', () => this.open('sell'));
        
        // Close modal
        this.backdrop?.addEventListener('click', () => this.close());
        
        // Quantity input - debounced preview
        this.quantityInput?.addEventListener('input', debounce(() => {
            this.updatePreview();
        }, 500));
        
        // Shortcut buttons
        document.querySelectorAll('.shortcut-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const percent = parseInt(btn.dataset.percent);
                this.applyShortcut(percent);
            });
        });
        
        // Accordion toggle
        this.accordionToggle?.addEventListener('click', () => this.toggleAccordion());
        
        // Confirm order
        this.confirmBtn?.addEventListener('click', () => this.executeOrder());
        
        // Slippage modal close
        this.closeSlippageBtn?.addEventListener('click', () => this.closeSlippageModal());
    }

    /**
     * Open modal for buy or sell
     */
    async open(type) {
        this.currentType = type;
        this.isOpen = true;
        
        // Update modal appearance
        this.modalTitle.textContent = type === 'buy' 
            ? `Acquista ${this.data.ticker}` 
            : `Vendi ${this.data.ticker}`;
        
        this.btnText.textContent = type === 'buy' 
            ? 'Conferma Acquisto' 
            : 'Conferma Vendita';
        
        this.confirmBtn.className = type === 'buy'
            ? 'btn-primary w-full disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2'
            : 'w-full py-4 rounded-xl font-bold text-lg transition-all bg-brand-danger text-text-main hover:bg-brand-danger-dark disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2';
        
        // Show/hide holdings info for sell
        if (type === 'sell') {
            this.holdingsInfo.classList.remove('hidden');
            this.holdingsQuantity.textContent = this.data.userHoldings;
        } else {
            this.holdingsInfo.classList.add('hidden');
        }
        
        // Reset quantity
        this.quantityInput.value = 1;
        
        // Show modal with proper visibility
        if (!this.modal || !this.backdrop) {
            console.error('OrderModal: Impossibile aprire modale - elementi non trovati');
            return;
        }
        
        // Ensure modal is visible in DOM (remove any hidden class if present)
        this.modal.classList.remove('hidden');
        this.backdrop.classList.remove('hidden');
        
        // Remove Tailwind transform classes that might conflict with inline styles
        this.modal.classList.remove('translate-y-full');
        
        // Force initial state
        this.backdrop.style.opacity = '0';
        this.modal.style.transform = 'translateY(100%)';
        
        // Animate in after a frame
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                this.backdrop.style.opacity = '1';
                this.modal.style.transform = 'translateY(0)';
            });
        });
        
        // Load preview
        const previewSuccess = await this.updatePreview();
        
        // Close modal if preview failed
        if (!previewSuccess) {
            this.close();
        }
    }

    /**
     * Close modal
     */
    close() {
        if (!this.modal || !this.backdrop) return;
        
        this.isOpen = false;
        this.backdrop.style.opacity = '0';
        this.modal.style.transform = 'translateY(100%)';
        
        setTimeout(() => {
            this.backdrop.classList.add('hidden');
            this.modal.classList.add('hidden');
            // Re-add Tailwind transform class for next open
            this.modal.classList.add('translate-y-full');
        }, 300);
    }

    /**
     * Update order preview
     * @returns {Promise<boolean>} Success status
     */
    async updatePreview() {
        const quantity = parseInt(this.quantityInput.value);
        
        if (!quantity || quantity < 1) {
            this.clearPreview();
            return false;
        }
        
        // Validate quantity for sell
        if (this.currentType === 'sell' && quantity > this.data.userHoldings) {
            NotificationService.error(`Non possiedi abbastanza azioni. Massimo: ${this.data.userHoldings}`);
            this.quantityInput.value = this.data.userHoldings;
            return false;
        }
        
        try {
            this.showLoading(true);
            
            const response = await TradingService.preview(
                this.data.memeId,
                this.currentType,
                quantity
            );
            
            if (response.success) {
                this.currentPreview = response.data;
                this.displayPreview(response.data);
                return true;
            }
            return false;
        } catch (error) {
            console.error('Anteprima fallita:', error);
            NotificationService.error(error.message || 'Errore nel calcolo dell\'anteprima');
            this.clearPreview();
            return false;
        } finally {
            this.showLoading(false);
        }
    }

    /**
     * Display preview data
     */
    displayPreview(data) {
        this.costSubtotal.textContent = formatCFU(data.subtotal);
        this.costFee.textContent = formatCFU(data.fee);
        this.costTotal.textContent = formatCFU(data.total);
        
        this.confirmBtn.disabled = false;
    }

    /**
     * Clear preview
     */
    clearPreview() {
        this.costSubtotal.textContent = '0.00 CFU';
        this.costFee.textContent = '0.00 CFU';
        this.costTotal.textContent = '0.00 CFU';
        this.confirmBtn.disabled = true;
        this.currentPreview = null;
    }

    /**
     * Execute the order (following UML flow)
     */
    async executeOrder() {
        if (!this.currentPreview) {
            NotificationService.error('Calcola prima l\'anteprima');
            return;
        }
        
        const quantity = parseInt(this.quantityInput.value);
        const expectedTotal = this.currentPreview.total;
        
        this.setButtonLoading(true);
        
        try {
            const response = await TradingService.execute(
                this.data.memeId,
                this.currentType,
                quantity,
                expectedTotal
            );
            
            if (response.success) {
                // Success! Update UI and show notification
                this.handleSuccessfulTrade(response);
            }
        } catch (error) {
            // Check if it's a slippage error (409 status)
            if (error.status === 409 && error.data.slippage_detected) {
                this.handleSlippage(error.data);
            } else {
                // Altro errore - mostra notifica ma mantieni modale aperta
                NotificationService.error(error.message || 'Errore nell\'esecuzione dell\'ordine');
            }
        } finally {
            this.setButtonLoading(false);
        }
    }

    /**
     * Handle successful trade
     */
    handleSuccessfulTrade(response) {
        // Update global data
        this.data.userBalance = response.data.new_balance;
        this.data.currentPrice = response.data.new_price;
        
        // Update balance display
        this.userBalance.textContent = formatCFU(this.data.userBalance);
        
        // Show success notification
        NotificationService.success(response.message);
        
        // Emit event for other components to update
        EventBus.emit('trade:executed', {
            type: this.currentType,
            quantity: response.data.transaction.quantity,
            price: response.data.new_price
        });
        
        // Close modal
        this.close();
        
        // Force price update
        EventBus.emit('price:forceUpdate');
    }

    /**
     * Handle slippage detection (as per UML)
     */
    handleSlippage(data) {
        // Close order modal first
        this.close();
        
        // Show slippage alert modal
        this.showSlippageModal(data.expected_total, data.actual_total);
    }

    /**
     * Show slippage alert modal
     */
    showSlippageModal(expectedTotal, actualTotal) {
        const change = actualTotal - expectedTotal;
        const changePercent = ((change / expectedTotal) * 100).toFixed(1);
        
        this.slippageExpected.textContent = formatCFU(expectedTotal);
        this.slippageActual.textContent = formatCFU(actualTotal);
        this.slippageChange.textContent = `${change >= 0 ? '+' : ''}${formatCFU(change)} (${changePercent}%)`;
        this.slippageChange.className = change >= 0 ? 'font-mono text-brand-danger' : 'font-mono text-brand';
        
        // Show modal
        this.slippageBackdrop.classList.remove('hidden');
        this.slippageModal.classList.remove('hidden');
    }

    /**
     * Close slippage modal and reopen order modal with fresh preview
     */
    async closeSlippageModal() {
        this.slippageBackdrop.classList.add('hidden');
        this.slippageModal.classList.add('hidden');
        
        // Reopen order modal with same type and quantity
        const quantity = this.quantityInput.value;
        await this.open(this.currentType);
        this.quantityInput.value = quantity;
        await this.updatePreview();
    }

    /**
     * Apply shortcut percentage
     */
    applyShortcut(percent) {
        let quantity = 0;
        
        if (this.currentType === 'sell') {
            // Sell: percentage of holdings
            quantity = Math.floor((this.data.userHoldings * percent) / 100);
        } else {
            // Buy: calculate max shares affordable with percentage of balance
            const availableBalance = (this.data.userBalance * percent) / 100;
            quantity = TradingService.calculateMaxBuy(
                availableBalance,
                this.data.basePrice,
                this.data.slope,
                this.data.circulatingSupply
            );
        }
        
        quantity = Math.max(1, quantity);
        this.quantityInput.value = quantity;
        this.updatePreview();
    }

    /**
     * Toggle cost accordion
     */
    toggleAccordion() {
        const isHidden = this.accordionContent.classList.contains('hidden');
        
        if (isHidden) {
            this.accordionContent.classList.remove('hidden');
            this.accordionIcon.style.transform = 'rotate(180deg)';
        } else {
            this.accordionContent.classList.add('hidden');
            this.accordionIcon.style.transform = 'rotate(0deg)';
        }
    }

    /**
     * Show/hide loading state
     */
    showLoading(show) {
        if (show) {
            this.loadingState.classList.remove('hidden');
            this.accordionToggle.style.display = 'none';
            this.accordionContent.classList.add('hidden');
        } else {
            this.loadingState.classList.add('hidden');
            this.accordionToggle.style.display = 'flex';
        }
    }

    /**
     * Set button loading state
     */
    setButtonLoading(loading) {
        this.confirmBtn.disabled = loading;
        
        if (loading) {
            this.btnSpinner.classList.remove('hidden');
            this.btnText.textContent = 'Elaborazione...';
        } else {
            this.btnSpinner.classList.add('hidden');
            this.btnText.textContent = this.currentType === 'buy' 
                ? 'Conferma Acquisto' 
                : 'Conferma Vendita';
        }
    }
}

export default OrderModal;
