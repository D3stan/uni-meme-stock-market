/**
 * Trading Page Controller - Orchestrates all components for Trade Station
 */
import Chart from '../components/Chart.js';
import OrderModal from '../components/OrderModal.js';
import PriceUpdateService from '../services/PriceUpdateService.js';
import EventBus from '../core/events.js';
import { formatCFU, formatPercentage } from '../utils/format.js';

class TradingPage {
    constructor() {
        // Get trading data from window (set by Blade template)
        this.data = window.TRADING_DATA;
        
        if (!this.data) {
            console.error('TRADING_DATA non trovato');
            return;
        }
        
        this.chart = null;
        this.orderModal = null;
        this.currentView = 'chart'; // 'chart' or 'meme'
        
        this.init();
    }

    init() {
        // Initialize components
        this.initChart();
        this.initOrderModal();
        this.initViewToggle();
        this.initPeriodSelector();
        this.initPriceUpdates();
        this.attachEventListeners();
    }

    /**
     * Initialize chart
     */
    initChart() {
        this.chart = new Chart('chart');
        this.chart.init(this.data.memeId);
    }

    /**
     * Initialize order modal
     */
    initOrderModal() {
        this.orderModal = new OrderModal(this.data);
    }

    /**
     * Initialize view toggle (Chart/Meme)
     */
    initViewToggle() {
        const chartBtn = document.getElementById('btn-chart-view');
        const memeBtn = document.getElementById('btn-meme-view');
        const chartContainer = document.getElementById('chart-container');
        const memeContainer = document.getElementById('meme-container');
        const statsSection = document.getElementById('stats-section');

        chartBtn?.addEventListener('click', () => {
            this.currentView = 'chart';
            
            // Update button styles
            chartBtn.className = 'flex items-center gap-2 px-6 py-2.5 rounded-full font-semibold transition-all bg-brand text-surface-50';
            memeBtn.className = 'flex items-center gap-2 px-6 py-2.5 rounded-full font-semibold transition-all bg-surface-200 text-text-muted hover:bg-surface-200/80';
            
            // Show chart, hide meme
            chartContainer.classList.remove('hidden');
            memeContainer.classList.add('hidden');
            statsSection?.classList.remove('hidden');
        });

        memeBtn?.addEventListener('click', () => {
            this.currentView = 'meme';
            
            // Update button styles
            memeBtn.className = 'flex items-center gap-2 px-6 py-2.5 rounded-full font-semibold transition-all bg-brand text-surface-50';
            chartBtn.className = 'flex items-center gap-2 px-6 py-2.5 rounded-full font-semibold transition-all bg-surface-200 text-text-muted hover:bg-surface-200/80';
            
            // Show meme, hide chart
            chartContainer.classList.add('hidden');
            memeContainer.classList.remove('hidden');
            statsSection?.classList.add('hidden');
        });
    }

    /**
     * Initialize period selector buttons
     */
    initPeriodSelector() {
        const periodBtns = document.querySelectorAll('.period-btn');
        
        periodBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const period = btn.dataset.period;
                
                // Update button styles
                periodBtns.forEach(b => {
                    b.className = 'period-btn px-6 py-2 rounded-full text-sm font-medium bg-surface-200 text-text-muted hover:bg-surface-200/80 transition-colors';
                });
                btn.className = 'period-btn px-6 py-2 rounded-full text-sm font-medium bg-brand text-surface-50 transition-colors';
                
                // Load new chart data
                this.chart.changePeriod(period);
            });
        });
    }

    /**
     * Initialize price update polling
     */
    initPriceUpdates() {
        // Start polling
        PriceUpdateService.start(this.data.memeId);
        
        // Listen for price updates
        EventBus.on('price:updated', (data) => {
            this.updatePrice(data);
        });
        
        // Listen for force update requests
        EventBus.on('price:forceUpdate', () => {
            PriceUpdateService.forceUpdate();
        });
    }

    /**
     * Update price display
     */
    updatePrice(data) {
        // Update stored data
        this.data.currentPrice = data.current_price;
        this.data.circulatingSupply = data.circulating_supply;
        
        // Update price header
        const priceElement = document.querySelector('.text-5xl.font-black.font-mono');
        if (priceElement) {
            const isPositive = data.price_change_24h.percentage >= 0;
            priceElement.textContent = `${data.current_price.toFixed(2)} CFU`;
            priceElement.className = `text-5xl font-black font-mono tracking-tight mb-3 ${isPositive ? 'text-brand' : 'text-brand-danger'}`;
        }
        
        // Update 24h change badge
        const badgeElement = document.querySelector('.inline-flex.items-center.gap-2.px-4.py-2.rounded-full');
        if (badgeElement) {
            const isPositive = data.price_change_24h.percentage >= 0;
            badgeElement.className = `inline-flex items-center gap-2 px-4 py-2 rounded-full border ${
                isPositive 
                    ? 'bg-brand/20 border-brand/50 text-brand' 
                    : 'bg-brand-danger/20 border-brand-danger/50 text-brand-danger'
            }`;
            
            const iconElement = badgeElement.querySelector('.material-icons');
            if (iconElement) {
                iconElement.textContent = isPositive ? 'arrow_upward' : 'arrow_downward';
            }
            
            const percentElement = badgeElement.querySelector('.font-semibold');
            if (percentElement) {
                percentElement.textContent = formatPercentage(data.price_change_24h.percentage);
            }
        }
        
        // Nota: I dati del grafico NON vengono aggiornati al polling per prevenire punti duplicati
        // Il grafico si aggiorna solo quando l'utente cambia periodo o dopo l'esecuzione di un trade
    }

    /**
     * Attach event listeners
     */
    attachEventListeners() {
        // Listen for successful trades
        EventBus.on('trade:executed', async (data) => {
            // Reload user holdings
            if (data.type === 'buy') {
                this.data.userHoldings += data.quantity;
            } else {
                this.data.userHoldings -= data.quantity;
            }
            
            // Force price update
            await PriceUpdateService.forceUpdate();
        });
        
        // Clean up on page unload
        window.addEventListener('beforeunload', () => {
            this.destroy();
        });
    }

    /**
     * Clean up resources
     */
    destroy() {
        // Stop price polling
        PriceUpdateService.stop();
        
        // Destroy chart
        if (this.chart) {
            this.chart.destroy();
        }
    }
}

// Inizializza quando il DOM è pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new TradingPage();
    });
} else {
    new TradingPage();
}
