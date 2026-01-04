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
        this.currentPeriod = '30d';
        this.memeId = null;
    }

    /**
     * Initialize the chart
     */
    init(memeId) {
        if (!this.container) {
            console.error(`Chart container #${this.containerId} not found`);
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
                    textColor: '#9ca3af',
                },
                grid: {
                    vertLines: { 
                        color: 'rgba(42, 46, 57, 0.3)' 
                    },
                    horzLines: { 
                        color: 'rgba(42, 46, 57, 0.3)' 
                    },
                },
                crosshair: {
                    mode: 0,
                },
                rightPriceScale: {
                    borderColor: 'rgba(197, 203, 206, 0.4)',
                },
                timeScale: {
                    borderColor: 'rgba(197, 203, 206, 0.4)',
                    timeVisible: true,
                    secondsVisible: false,
                },
            });

            // Create area series with green gradient
            this.series = this.chart.addSeries(AreaSeries, {
                topColor: 'rgba(34, 197, 94, 0.4)',
                bottomColor: 'rgba(34, 197, 94, 0.0)',
                lineColor: 'rgba(34, 197, 94, 1)',
                lineWidth: 2,
            });

            // Handle window resize
            window.addEventListener('resize', () => this.handleResize());

            // Load initial data
            this.loadData(this.currentPeriod);
        } catch (error) {
            console.error('Chart initialization failed:', error);
            // Show fallback message
            this.container.innerHTML = '<div class="flex items-center justify-center h-full text-gray-500"><p>Chart loading error. Please refresh.</p></div>';
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
                this.chart.timeScale().fitContent();
            } else {
                console.warn('No price history data available');
            }
        } catch (error) {
            console.error('Failed to load chart data:', error);
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
