<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
            isDarkMode: localStorage.getItem('darkMode') === 'true'
        }" :class="{ 'dark': isDarkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::get('store_name', config('app.name', 'Laravel')) }}</title>

        <!-- Favicon -->
        @php
            $faviconLogo = \App\Models\Setting::get('store_logo');
        @endphp
        @if($faviconLogo)
            <link rel="icon" type="image/x-icon" href="{{ str_starts_with($faviconLogo, 'data:') ? $faviconLogo : asset($faviconLogo) }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 dark:text-gray-300 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-cyber-darker dark:via-cyber-dark dark:to-cyber-darker">
            <!-- Dark Mode Toggle -->
            <div class="absolute top-4 right-4">
                <button @click="isDarkMode = !isDarkMode; localStorage.setItem('darkMode', isDarkMode)" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-cyber-purple/10 hover:text-gray-900 dark:hover:text-cyber-cyan focus:outline-none transition-all duration-200" title="Toggle Dark Mode">
                    <svg x-show="!isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <svg x-show="isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                    </svg>
                </button>
            </div>
            
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-blue-600 dark:text-cyber-cyan drop-shadow-lg dark:drop-shadow-neon-cyan" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-cyber-dark/60 backdrop-blur-xl border border-gray-200 dark:border-cyber-purple/30 shadow-lg dark:shadow-neon-purple overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
