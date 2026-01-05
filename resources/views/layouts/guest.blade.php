<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ideas Electricals') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect fill='%236366f1' rx='20' width='100' height='100'/><text x='50' y='68' font-size='45' font-weight='bold' fill='white' text-anchor='middle' font-family='Arial'>IE</text></svg>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-slate-900">
        <div class="min-h-screen flex flex-col justify-center items-center p-4">
            <!-- Background decorative elements -->
            <div class="fixed inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-violet-500/10 rounded-full blur-3xl"></div>
            </div>

            <div class="relative z-10 w-full max-w-md">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex flex-col items-center gap-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                            <span class="text-white font-extrabold text-xl">IE</span>
                        </div>
                        <span class="text-white font-bold text-xl">{{ config('app.name', 'Ideas Electricals') }}</span>
                    </a>
                </div>

                <!-- Card -->
                <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700/50 rounded-2xl shadow-xl p-8">
                    {{ $slot }}
                </div>

                <!-- Footer -->
                <p class="text-center text-slate-500 text-sm mt-6">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
