/**
 * Portfolio Page JavaScript
 * Handles asset allocation chart and net worth visibility toggle
 */

import Chart from 'chart.js/auto';

class PortfolioManager {
    constructor() {
        this.netWorthVisible = true;
        this.originalValue = null;
        this.valueElement = null;
        this.iconElement = null;
        
        this.init();
    }

    init() {
        // Initialize allocation chart if element exists
        const chartElement = document.getElementById('allocation-chart');
        if (chartElement) {
            this.initAllocationChart(chartElement);
        }

        // Initialize net worth visibility toggle
        this.initNetWorthToggle();
    }

    /**
     * Initialize the asset allocation donut chart
     */
    initAllocationChart(ctx) {
        const invested = parseFloat(ctx.dataset.invested || 0);
        const liquid = parseFloat(ctx.dataset.liquid || 0);

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Investito', 'Liquidità'],
                datasets: [{
                    data: [invested, liquid],
                    backgroundColor: ['#10B981', '#4B5563'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: true,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return label + ': ' + value.toFixed(2) + ' CFU';
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Initialize net worth visibility toggle
     */
    initNetWorthToggle() {
        this.valueElement = document.getElementById('net-worth-value');
        this.iconElement = document.getElementById('visibility-icon');
        
        if (!this.valueElement || !this.iconElement) return;

        this.originalValue = this.valueElement.textContent.trim();

        // Attach to window for onclick handler
        window.toggleNetWorthVisibility = () => this.toggleVisibility();
    }

    /**
     * Toggle net worth visibility
     */
    toggleVisibility() {
        this.netWorthVisible = !this.netWorthVisible;

        if (this.netWorthVisible) {
            this.valueElement.textContent = this.originalValue;
            this.iconElement.textContent = 'visibility';
        } else {
            // Generate dots based on original value length
            const dots = '•'.repeat(this.originalValue.replace(/[,.\s]/g, '').length);
            this.valueElement.textContent = dots;
            this.iconElement.textContent = 'visibility_off';
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new PortfolioManager();
});
