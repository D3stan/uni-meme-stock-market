<!DOCTYPE html>
<html lang="it" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Componenti</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 p-8">
    <div class="max-w-md mx-auto space-y-6">
        <h1 class="text-2xl font-bold text-white mb-6">Test Input Component</h1>
        
        {{-- Input base --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-white">Email</label>
            <x-forms.input 
                name="email" 
                type="email" 
                placeholder="nome.cognome@studio.unibo.it" 
            />
        </div>
        
        {{-- Input con valore --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-white">Password</label>
            <x-forms.input 
                name="username" 
                type="password" 
                placeholder="Password"
            />
        </div>
        
        {{-- Input disabilitato --}}
        <div>
            <label class="block mb-2 text-sm font-medium text-white">CFU (Disabilitato)</label>
            <x-forms.input 
                name="cfu" 
                value="1000" 
                disabled 
            />
        </div>
    </div>
</body>
</html>