<!DOCTYPE html>
@props(['active' => null, 'balance' => null])
<html lang="it" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'AlmaStreet' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body class="bg-background text-white font-sans antialiased">

    {{-- Navigation bar component (renders fixed navs) --}}
    <x-navigation.navigation-bar :active="$active ?? null" :balance="$balance ?? null" />

    {{-- Top spacer: only mobile --}}
    <div class="lg:hidden h-8" aria-hidden="true"></div>

    <main>
        {{ $slot }}
    </main>
</body>
</html>
