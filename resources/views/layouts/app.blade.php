<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
            sidebarOpen: localStorage.getItem('sidebarOpen') !== null ? localStorage.getItem('sidebarOpen') === 'true' : window.innerWidth >= 1024,
            sidebarMinimized: localStorage.getItem('sidebarMinimized') === 'true',
            isDarkMode: localStorage.getItem('darkMode') === 'true'
        }" :class="{ 'dark': isDarkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::get('store_name', config('app.name', 'BN Boutique')) }}</title>

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
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100 min-h-screen bg-gray-50 dark:bg-gray-900 flex transition-colors duration-300">
            <!-- Sidebar Layout -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div :class="!sidebarOpen ? 'lg:pl-0' : (sidebarMinimized ? 'lg:pl-20' : 'lg:pl-64')" class="flex-1 flex flex-col min-w-0 transition-all duration-300 ease-in-out">
                <!-- Top Navigation Bar -->
                <div class="sticky top-0 z-20 flex items-center justify-between h-16 bg-white dark:bg-gray-800 px-6 border-b border-gray-200 dark:border-gray-700 shrink-0 shadow-sm">
                    <!-- Left Side: Toggle & Dynamic Brand -->
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = !sidebarOpen; localStorage.setItem('sidebarOpen', sidebarOpen)" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <div x-show="!sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="flex items-center gap-3">
                            <x-application-logo class="h-8 w-auto object-contain rounded" />
                            <span class="font-bold text-gray-900 dark:text-white text-base">
                                {{ \App\Models\Setting::get('store_name', config('app.name', 'BN Boutique')) }}
                            </span>
                        </div>
                    </div>

                    <!-- Right Side: Navigation Actions & Profile Info -->
                    <div class="flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button @click="isDarkMode = !isDarkMode; localStorage.setItem('darkMode', isDarkMode)" class="p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150" title="Toggle Dark Mode">
                            <svg x-show="!isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <svg x-show="isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </button>

                        <!-- Cart Icon -->
                        <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150" title="Keranjang Belanja">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"></path>
                            </svg>
                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                    {{ array_sum(array_column(session('cart'), 'quantity')) }}
                                </span>
                            @endif
                        </a>

                        <!-- Storefront Quick Link -->
                        <a href="{{ route('home') }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700/50 hover:bg-gray-200 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg transition duration-150">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span class="hidden sm:inline">Lihat Toko</span>
                        </a>

                        <span class="hidden md:inline text-xs font-medium text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                        </span>

                        <div class="h-5 w-px bg-gray-200 dark:bg-gray-600"></div>

                        <!-- Profile Info Display -->
                        <div class="flex items-center gap-3">
                            <div class="hidden sm:block text-right">
                                <span class="block text-xs font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                                <span class="block text-[10px] font-semibold text-gray-500 dark:text-gray-400 capitalize">{{ Auth::user()->role }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-grow">
                    {{ $slot }}
                </main>
            </div>

        <!-- SweetAlert2 Flash Messages -->
        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    window.Swal.fire({
                        title: 'Sukses!',
                        text: "{{ session('success') }}",
                        icon: 'success',
                        confirmButtonColor: '#4f46e5',
                    });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    window.Swal.fire({
                        title: 'Gagal!',
                        text: "{{ session('error') }}",
                        icon: 'error',
                        confirmButtonColor: '#ef4444',
                    });
                });
            </script>
        @endif
    </body>
</html>
