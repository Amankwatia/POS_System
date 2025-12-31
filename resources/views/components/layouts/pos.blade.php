<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="h-full font-sans antialiased" x-data="{ sidebarOpen: true, mobileMenuOpen: false }">
    <div class="min-h-screen bg-surface">
        <!-- Mobile menu backdrop -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
             @click="mobileMenuOpen = false">
        </div>

        <!-- Sidebar -->
        @include('components.pos.sidebar')

        <!-- Top Navigation Bar -->
        @include('components.pos.topbar')

        <!-- Main Content -->
        <main class="transition-all duration-300 ease-in-out pt-topbar min-h-screen bg-surface"
              :class="sidebarOpen ? 'lg:ml-sidebar' : 'lg:ml-sidebar-collapsed'">
            <div class="p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
