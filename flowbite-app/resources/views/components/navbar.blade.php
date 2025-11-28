<!-- Mobile Bottom Navigation (visible on mobile) -->
<nav class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 md:hidden">
    <div class="grid h-full max-w-lg grid-cols-4 mx-auto font-medium">
        <a href="/" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-700 group">
            <svg class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
            </svg>
            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">Home</span>
        </a>
        <a href="/portfolio" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-700 group">
            <svg class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M11.074 4 8.442.408A.95.95 0 0 0 7.014.254L2.926 4h8.148ZM9 13v-1a4 4 0 0 1 4-4h6V6a1 1 0 0 0-1-1H1a1 1 0 0 0-1 1v13a1 1 0 0 0 1 1h17a1 1 0 0 0 1-1v-2h-6a4 4 0 0 1-4-4Z"/>
                <path d="M19 10h-6a2 2 0 0 0-2 2v1a2 2 0 0 0 2 2h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1Zm-4.5 3.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM12.62 4h2.78L12.539.41a1.086 1.086 0 1 0-1.7 1.352L12.62 4Z"/>
            </svg>
            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">Portfolio</span>
        </a>
        <a href="/profile" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-700 group">
            <svg class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"/>
            </svg>
            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-500">Profile</span>
        </a>
        <button type="button" id="logout-btn-mobile" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-700 group">
            <svg class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
            </svg>
            <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-500">Logout</span>
        </button>
    </div>
</nav>

<!-- Desktop Top Navigation (visible on desktop) -->
<nav class="hidden md:block fixed top-0 left-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-xl font-bold text-blue-600 dark:text-blue-500">
                        AlmaStreet 📈
                    </a>
                </div>
                <!-- Navigation Links -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <a href="/" class="border-blue-500 text-gray-900 dark:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Marketplace
                    </a>
                    <a href="/portfolio" class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Portfolio
                    </a>
                    <a href="/profile" class="border-transparent text-gray-500 dark:text-gray-400 hover:border-gray-300 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                        Profile
                    </a>
                </div>
            </div>
            <!-- Right side -->
            <div class="flex items-center">
                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 mr-4" id="cfu-balance-display">
                    0.00 CFU
                </span>
                <button type="button" id="logout-btn-desktop" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Logout
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    // Handle logout
    document.getElementById('logout-btn-mobile')?.addEventListener('click', logout);
    document.getElementById('logout-btn-desktop')?.addEventListener('click', logout);

    async function logout() {
        try {
            const response = await fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                window.location.href = '/login';
            }
        } catch (error) {
            console.error('Logout failed:', error);
        }
    }

    // Load user balance
    async function loadUserBalance() {
        try {
            const response = await fetch('/api/user/profile', {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (response.ok) {
                const data = await response.json();
                const balanceElement = document.getElementById('cfu-balance-display');
                if (balanceElement && data.user) {
                    balanceElement.textContent = `${parseFloat(data.user.cfu_balance).toFixed(2)} CFU`;
                }
            }
        } catch (error) {
            console.error('Failed to load balance:', error);
        }
    }

    // Load balance on page load
    if (document.querySelector('meta[name="csrf-token"]')) {
        loadUserBalance();
    }
</script>
