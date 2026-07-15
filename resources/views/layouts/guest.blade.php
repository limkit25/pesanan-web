<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-outfit text-gray-900 antialiased relative min-h-screen">
        <!-- Background Gradient -->
        <div class="fixed inset-0 pointer-events-none z-[-1]">
            <div class="absolute inset-0 bg-gradient-to-br from-pink-50 via-white to-orange-50"></div>
            <!-- Decorative blob -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-pink-400 opacity-20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-96 h-96 rounded-full bg-orange-400 opacity-20 blur-3xl"></div>
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative z-10">
            <div class="mb-6 text-center">
                <a href="/" class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-orange-400 to-pink-500 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-lg mb-4">
                        P
                    </div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-500 to-pink-500 bg-clip-text text-transparent">Pesanan Web</h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white/70 backdrop-blur-xl border border-white/50 shadow-2xl overflow-hidden sm:rounded-3xl">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-sm text-gray-500">
                &copy; {{ date('Y') }} Pesanan Web. All rights reserved.
            </div>
        </div>
    </body>
</html>
