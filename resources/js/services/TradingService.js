/**
 * Trading Service - Handles all trading operations
 */
import API from '../core/api.js';

class TradingService {
    /**
     * Preview an order (buy or sell)
     */
    async preview(memeId, type, quantity) {
        return await API.post('/api/trade/preview', {
            meme_id: memeId,
            type: type,
            quantity: parseInt(quantity)
        });
    }

    /**
     * Execute a trade with slippage protection
     */
    async execute(memeId, type, quantity, expectedTotal) {
        return await API.post('/api/trade/execute', {
            meme_id: memeId,
            type: type,
            quantity: parseInt(quantity),
            expected_total: parseFloat(expectedTotal)
        });
    }

    /**
     * Get price history for chart
     */
    async getPriceHistory(memeId, period = '1d') {
        const url = `/api/trade/${memeId}/price-history/${period}`;
        return await API.get(url);
    }

    /**
     * Get current market data for a meme
     */
    async getMarketData(memeId) {
        const url = `/api/trade/${memeId}/market-data`;
        return await API.get(url);
    }

    /**
     * Get user's current holdings for a meme
     */
    async getHoldings(memeId) {
        const url = `/api/trade/${memeId}/holdings`;
        return await API.get(url);
    }

    /**
     * Calculate maximum shares user can buy with available balance
     * Uses iterative approach due to bonding curve
     */
    calculateMaxBuy(balance, basePrice, slope, currentSupply) {
        let quantity = 0;
        let totalCost = 0;
        const taxRate = 0.02;

        // Iteratively find max quantity
        while (quantity < 10000) { // Safety limit
            quantity++;
            
            // Calculate cost using integral formula
            const subtotal = (basePrice * quantity) + 
                           ((slope / 2) * (Math.pow(currentSupply + quantity, 2) - Math.pow(currentSupply, 2)));
            const fee = subtotal * taxRate;
            totalCost = subtotal + fee;

            if (totalCost > balance) {
                return Math.max(0, quantity - 1);
            }
        }

        return quantity;
    }
}

export default new TradingService();
