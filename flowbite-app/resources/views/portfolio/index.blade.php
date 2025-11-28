@extends('app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Portfolio</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Track your investments and net worth</p>
    </div>

    <!-- Net Worth Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="mb-4 md:mb-0">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Net Worth</p>
                <p class="text-4xl font-bold text-gray-900 dark:text-white" id="net-worth">
                    Loading...
                </p>
            </div>
            <div class="w-48 h-48">
                <canvas id="allocation-chart"></canvas>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Liquid Balance</p>
                <p class="text-2xl font-semibold text-green-600 dark:text-green-400" id="liquid-balance">
                    0.00 CFU
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">Invested Value</p>
                <p class="text-2xl font-semibold text-blue-600 dark:text-blue-400" id="invested-value">
                    0.00 CFU
                </p>
            </div>
        </div>
    </div>

    <!-- Holdings Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">My Holdings</h2>
        
        <!-- Empty State -->
        <div id="empty-state" class="text-center py-12">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No holdings yet</h3>
            <p class="mt-2 text-gray-500 dark:text-gray-400">Start trading to build your portfolio!</p>
            <a href="/" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                Browse Marketplace
            </a>
        </div>

        <!-- Holdings List (hidden by default) -->
        <div id="holdings-list" class="hidden space-y-4">
            <!-- Holdings will be dynamically loaded here -->
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let allocationChart = null;

    async function loadPortfolio() {
        try {
            const response = await fetch('/api/user/profile', {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                const data = await response.json();
                const user = data.user;
                
                // Update balances
                const liquidBalance = parseFloat(user.cfu_balance);
                const investedValue = 0; // TODO: Calculate from portfolio once we have memes
                const netWorth = liquidBalance + investedValue;
                
                document.getElementById('liquid-balance').textContent = `${liquidBalance.toFixed(2)} CFU`;
                document.getElementById('invested-value').textContent = `${investedValue.toFixed(2)} CFU`;
                document.getElementById('net-worth').textContent = `${netWorth.toFixed(2)} CFU`;
                
                // Create/Update chart
                createAllocationChart(liquidBalance, investedValue);
                
                // For now, show empty state since we don't have portfolio data yet
                if (investedValue === 0) {
                    document.getElementById('empty-state').classList.remove('hidden');
                    document.getElementById('holdings-list').classList.add('hidden');
                }
            }
        } catch (error) {
            console.error('Failed to load portfolio:', error);
        }
    }

    function createAllocationChart(liquid, invested) {
        const ctx = document.getElementById('allocation-chart');
        
        if (allocationChart) {
            allocationChart.destroy();
        }
        
        allocationChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Liquid', 'Invested'],
                datasets: [{
                    data: [liquid, invested],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(59, 130, 246)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    }

    // Load portfolio on page load
    loadPortfolio();
</script>
@endsection
