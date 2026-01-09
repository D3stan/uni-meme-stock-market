/**
 * Price Update Service - Polls for price updates every 10 seconds
 */
import API from '../core/api.js';
import EventBus from '../core/events.js';

class PriceUpdateService {
    constructor() {
        this.interval = null;
        this.pollInterval = 10000; // 10 seconds
        this.memeId = null;
    }

    /**
     * Start polling for price updates
     */
    start(memeId) {
        this.memeId = memeId;
        this.poll(); // Initial poll
        
        this.interval = setInterval(() => {
            this.poll();
        }, this.pollInterval);
    }

    /**
     * Stop polling
     */
    stop() {
        if (this.interval) {
            clearInterval(this.interval);
            this.interval = null;
        }
    }

    /**
     * Fetch current market data
     */
    async poll() {
        if (!this.memeId) return;

        try {
            const response = await API.get(`/api/trade/${this.memeId}/market-data`);
            
            if (response.success) {
                // Emit event with new data
                EventBus.emit('price:updated', response.data);
            }
        } catch (error) {
            console.error('Aggiornamento prezzo fallito:', error);
        }
    }

    /**
     * Force immediate update
     */
    async forceUpdate() {
        await this.poll();
    }
}

export default new PriceUpdateService();
