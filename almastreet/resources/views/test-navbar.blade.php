<!DOCTYPE html>
<html lang="it" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Navigation Bar - AlmaStreet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body class="bg-[#0f2216] text-white font-sans antialiased">
    <div class="pb-20 lg:pb-8 lg:pt-20">
        <div class="max-w-4xl mx-auto px-4 py-8 space-y-12">
            
            {{-- Header --}}
            <div class="text-center space-y-4">
                <h1 class="text-4xl font-black text-green-500">Navigation Bar Test</h1>
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 rounded-lg text-sm">
                    <span class="material-icons text-green-500 text-base">info</span>
                    <span>Ridimensiona la finestra per vedere la versione desktop</span>
                </div>
            </div>
        </div>
    </div>

    <x-navigation-bar active="market" />

</body>
</html>
