<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AlmaStreet - The Academic Stock Market</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
    <div class="min-h-screen flex flex-col items-center justify-center px-6 py-12">
        <!-- Hero Section -->
        <div class="text-center max-w-4xl">
            <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-4">
                <span class="text-7xl">📈</span>
                <br>
                AlmaStreet
            </h1>
            <p class="text-2xl text-gray-700 dark:text-gray-300 mb-4">
                The Academic Stock Market
            </p>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                Trade meme stocks with your fellow students. Buy low, sell high, and dominate the Dean's List! 🚀
            </p>
            
            <!-- Call to Action -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                <a href="/register" class="w-full sm:w-auto px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200">
                    Start Trading - Get 100 CFU Free! 🎁
                </a>
                <a href="/login" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-gray-50 text-blue-600 font-semibold rounded-lg shadow-lg border-2 border-blue-600 transform hover:scale-105 transition-all duration-200">
                    Login to Your Account
                </a>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="text-4xl mb-4">💰</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">100 CFU Bonus</h3>
                    <p class="text-gray-600 dark:text-gray-400">Start trading immediately with 100 free CFU credits on registration</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Real-Time Trading</h3>
                    <p class="text-gray-600 dark:text-gray-400">Dynamic prices powered by AMM bonding curves - buy and sell instantly</p>
                </div>
                
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <div class="text-4xl mb-4">🏆</div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Compete & Win</h3>
                    <p class="text-gray-600 dark:text-gray-400">Climb the leaderboard, earn badges, and become a trading legend</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-16 text-gray-500 dark:text-gray-400 text-sm">
                <p>Built for University of Bologna students</p>
                <p class="mt-2">Must use @unibo.it or @studio.unibo.it email to register</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>
</html>
