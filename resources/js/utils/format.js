/**
 * Formatting utilities for numbers, currency, and dates
 */

/**
 * Format number as CFU currency
 */
export function formatCFU(amount, decimals = 2) {
    return `${Number(amount).toFixed(decimals)} CFU`;
}

/**
 * Format number with thousands separator
 */
export function formatNumber(num, decimals = 0) {
    return Number(num).toLocaleString('it-IT', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    });
}

/**
 * Format percentage with sign
 */
export function formatPercentage(value, decimals = 1) {
    const sign = value >= 0 ? '+' : '';
    return `${sign}${Number(value).toFixed(decimals)}%`;
}

/**
 * Format large numbers (e.g., 1000 -> 1k, 1000000 -> 1M)
 */
export function formatCompact(num) {
    if (num >= 1000000) {
        return `${(num / 1000000).toFixed(1)}M`;
    }
    if (num >= 1000) {
        return `${(num / 1000).toFixed(1)}k`;
    }
    return num.toString();
}

/**
 * Format timestamp for chart (HH:mm)
 */
export function formatTime(timestamp) {
    const date = new Date(timestamp * 1000);
    return date.toLocaleTimeString('it-IT', { hour: '2-digit', minute: '2-digit' });
}
