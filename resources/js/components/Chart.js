/**
 * Chart Component - TradingView Lightweight Charts wrapper
 */
import { createChart, AreaSeries } from 'lightweight-charts';
import TradingService from '../services/TradingService.js';

class Chart {
    constructor(containerId) {
        this.containerId = containerId;
        this.container = document.getElementById(containerId);
        this.chart = null;
        this.series = null;
        this.currentPeriod = '1d';
        this.memeId = null;
    }

    /**
     * Initialize the chart
     */
    init(memeId) {
        if (!this.container) {
            console.error(`Contenitore grafico #${this.containerId} non trovato`);
            return;
        }

        this.memeId = memeId;

        try {
            // Create chart with dark theme
            this.chart = createChart(this.container, {
                width: this.container.clientWidth,
                height: 300,
                layout: {
                    background: {
                        color: 'transparent'
                    },
                    textColor: '#94a3b8',
                },
                grid: {
                    vertLines: { 
                        color: 'rgba(51, 65, 85, 0.1)' 
                    },
                    horzLines: { 
                        color: 'rgba(51, 65, 85, 0.1)' 
                    },
                },
                crosshair: {
                    mode: 0,
                },
                rightPriceScale: {
                    borderColor: 'rgba(51, 65, 85, 0.4)',
                },
                timeScale: {
                    borderColor: 'rgba(51, 65, 85, 0.4)',
                    timeVisible: true,
                    secondsVisible: false,
                },
            });

            // Create area series with brand green gradient
            this.series = this.chart.addSeries(AreaSeries, {
                topColor: 'rgba(16, 185, 129, 0.4)',
                bottomColor: 'rgba(16, 185, 129, 0.0)',
                lineColor: 'rgba(16, 185, 129, 1)',
                lineWidth: 2,
            });

            // Handle window resize
            window.addEventListener('resize', () => this.handleResize());

            // Load initial data
            this.loadData(this.currentPeriod);
        } catch (error) {
            console.error('Inizializzazione grafico fallita:', error);
            // Mostra messaggio di fallback
            this.container.innerHTML = '<div class="flex items-center justify-center h-full text-text-muted"><p>Errore caricamento grafico. Ricarica la pagina.</p></div>';
        }
    }

    /**
     * Load price history data
     */
    async loadData(period) {
        try {
            const response = await TradingService.getPriceHistory(this.memeId, period);
            
            if (response.success && response.data.length > 0) {
                this.series.setData(response.data);
                this.updateTimeScale(period);
                this.chart.timeScale().fitContent();
            } else {
                console.warn('Nessuno storico prezzi disponibile');
            }
        } catch (error) {
            console.error('Caricamento dati grafico fallito:', error);
            this.showEmptyState();
        }
    }

    /**
     * Show empty state when no data available
     */
    showEmptyState() {
        const now = Math.floor(Date.now() / 1000);
        const placeholderData = [
            { time: now - 86400, value: window.TRADING_DATA?.currentPrice || 1 },
            { time: now, value: window.TRADING_DATA?.currentPrice || 1 }
        ];
        this.series.setData(placeholderData);
        this.chart.timeScale().fitContent();
    }

    /**
     * Update time scale format based on period
     */
    updateTimeScale(period) {
        if (!this.chart) return;
        
        // For hourly periods (1h, 4h), show time
        // For daily periods (1d), show only date
        const showTime = period === '1h' || period === '4h';
        
        this.chart.applyOptions({
            timeScale: {
                timeVisible: showTime,
                secondsVisible: false,
            }
        });
    }

    /**
     * Change time period
     */
    changePeriod(period) {
        this.currentPeriod = period;
        this.loadData(period);
    }

    /**
     * Update with new price point (for real-time updates)
     */
    update(price, timestamp) {
        if (!this.series) return;

        const dataPoint = {
            time: timestamp || Math.floor(Date.now() / 1000),
            value: parseFloat(price)
        };

        this.series.update(dataPoint);
    }

    /**
     * Handle window resize
     */
    handleResize() {
        if (!this.chart || !this.container) return;
        this.chart.applyOptions({ width: this.container.clientWidth });
    }

    /**
     * Destroy chart instance
     */
    destroy() {
        if (this.chart) {
            this.chart.remove();
            this.chart = null;
        }
    }
}

export default Chart;
