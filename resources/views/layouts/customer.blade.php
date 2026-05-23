<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
            isDarkMode: localStorage.getItem('darkMode') === 'true'
        }" :class="{ 'dark': isDarkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ \App\Models\Setting::get('store_name', config('app.name', 'Toko Online')) }}</title>

        <!-- Favicon -->
        @php
            $faviconLogo = \App\Models\Setting::get('store_logo');
        @endphp
        @if($faviconLogo)
            <link rel="icon" type="image/x-icon" href="{{ str_starts_with($faviconLogo, 'data:') ? $faviconLogo : asset($faviconLogo) }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,750&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        @php
            $storeName = \App\Models\Setting::get('store_name', config('app.name', 'Toko Online'));
            $storeLogo = \App\Models\Setting::get('store_logo');
        @endphp
        <div class="min-h-screen flex flex-col bg-gradient-to-br from-[#91ebff] to-white">
            <!-- Navbar -->
            <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border-b border-gray-100 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo / Store Name -->
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                @if($storeLogo)
                                    <img src="{{ str_starts_with($storeLogo, 'data:') ? $storeLogo : asset($storeLogo) }}" alt="{{ $storeName }}" class="h-9 w-auto object-contain rounded-md" />
                                @endif
                                <span class="text-xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-indigo-650 via-indigo-500 to-[#67dbf5] dark:from-[#91ebff] dark:to-white">
                                    {{ $storeName }}
                                </span>
                            </a>
                        </div>

                        <!-- Right Side Navbar -->
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
                            <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition duration-150" title="Keranjang Belanja">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                @if(session('cart') && count(session('cart')) > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                        {{ array_sum(array_column(session('cart'), 'quantity')) }}
                                    </span>
                                @endif
                            </a>

                            <!-- Shipping Icon (Only for authenticated users) -->
                            @auth
                                @php
                                    $shippingCount = \App\Models\Order::where('user_id', auth()->id())
                                        ->where('status', 'shipping')
                                        ->count();
                                @endphp
                                @if($shippingCount > 0)
                                    <a href="{{ route('orders.index') }}" class="relative p-2 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-200 transition duration-150" title="Pesanan Sedang Dikirim">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                                        </svg>
                                        <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-blue-600 rounded-full animate-pulse">
                                            {{ $shippingCount }}
                                        </span>
                                    </a>
                                @endif
                            @endauth

                            <!-- Authentication Links -->
                            @auth
                                <div class="hidden sm:flex sm:items-center sm:ms-3">
                                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition duration-150 mr-4">
                                        {{ __('Dashboard') }}
                                    </a>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:underline transition duration-150 mr-4">
                                            Admin
                                        </a>
                                    @else
                                        <a href="{{ route('orders.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition duration-150 mr-4">
                                            Riwayat Belanja
                                        </a>
                                    @endif
                                    <!-- Logout Form -->
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline">
                                            {{ __('Log Out') }}
                                        </button>
                                    </form>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition duration-150">Log in</a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md shadow-sm transition duration-150">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-grow">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 py-6 mt-12">
                <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    &copy; {{ date('Y') }} {{ $storeName }}. All rights reserved.
                </div>
            </footer>
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
