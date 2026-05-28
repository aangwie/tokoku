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
    <body class="font-sans antialiased bg-gray-50 dark:bg-cyber-darker text-gray-900 dark:text-gray-300">
        @php
            $storeName = \App\Models\Setting::get('store_name', config('app.name', 'Toko Online'));
            $storeLogo = \App\Models\Setting::get('store_logo');
        @endphp
        <div class="min-h-screen flex flex-col bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-cyber-darker dark:via-cyber-dark dark:to-cyber-darker">
            <!-- Navbar -->
            <nav class="bg-white/80 dark:bg-cyber-dark/60 backdrop-blur-xl border-b border-gray-200 dark:border-cyber-purple/30 sticky top-0 z-50 shadow-sm dark:shadow-neon-purple-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex items-center">
                            <!-- Logo / Store Name -->
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                @if($storeLogo)
                                    <img src="{{ str_starts_with($storeLogo, 'data:') ? $storeLogo : asset($storeLogo) }}" alt="{{ $storeName }}" class="h-9 w-auto object-contain rounded-md" />
                                @endif
                                <span class="text-xl font-extrabold tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-cyber-purple via-cyber-cyan to-cyber-purple">
                                    {{ $storeName }}
                                </span>
                            </a>
                        </div>

                        <!-- Right Side Navbar -->
                        <div class="flex items-center gap-4">
                            <!-- Dark Mode Toggle -->
                            <button @click="isDarkMode = !isDarkMode; localStorage.setItem('darkMode', isDarkMode)" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-cyber-purple/10 hover:text-gray-900 dark:hover:text-cyber-cyan focus:outline-none transition-all duration-200" title="Toggle Dark Mode">
                                <svg x-show="!isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                <svg x-show="isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                </svg>
                            </button>

                            <!-- Cart Icon -->
                            <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-cyber-cyan transition-all duration-200" title="Keranjang Belanja">
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
                                    <a href="{{ route('orders.index') }}" class="relative p-2 text-blue-600 dark:text-cyber-cyan hover:text-blue-800 dark:hover:text-cyber-purple transition-all duration-200" title="Pesanan Sedang Dikirim">
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
                                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all duration-200 mr-4">
                                        {{ __('Dashboard') }}
                                    </a>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-medium text-blue-600 dark:text-cyber-cyan hover:text-blue-800 dark:hover:text-cyber-purple hover:underline transition-all duration-200 mr-4">
                                            Admin
                                        </a>
                                    @else
                                        <a href="{{ route('orders.index') }}" class="text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all duration-200 mr-4">
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
                                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all duration-200">Log in</a>
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-500 dark:from-cyber-purple dark:to-cyber-cyan hover:shadow-lg dark:hover:shadow-neon-cyan rounded-lg transition-all duration-200">Register</a>
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
            <footer class="bg-white dark:bg-cyber-dark/60 backdrop-blur-xl border-t border-gray-200 dark:border-cyber-purple/30 py-8 mt-12">
                <div class="max-w-7xl mx-auto px-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <!-- Store Info -->
                        <div class="text-center md:text-left">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ $storeName }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                Toko online terpercaya untuk kebutuhan Anda
                            </p>
                            @php
                                $storeAddress = \App\Models\Setting::get('store_address', '');
                            @endphp
                            @if($storeAddress)
                                <div class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-cyber-cyan mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span class="leading-relaxed">{{ $storeAddress }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Contact Info -->
                        @php
                            $storeWhatsApp = \App\Models\Setting::get('store_whatsapp', '');
                        @endphp
                        <div class="text-center">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Hubungi Kami</h3>
                            @if($storeWhatsApp)
                                <a href="https://wa.me/{{ $storeWhatsApp }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition duration-150 shadow-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <span>WhatsApp</span>
                                </a>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                                    {{ $storeWhatsApp }}
                                </p>
                            @endif
                        </div>
                        
                        <!-- Footer Links -->
                        <div class="text-center md:text-right">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Informasi</h3>
                            <div class="flex flex-col gap-2 text-sm">
                                <a href="{{ route('pages.terms') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-cyber-cyan transition-all duration-200">
                                    Syarat & Ketentuan
                                </a>
                                <a href="{{ route('pages.refund-policy') }}" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-cyber-cyan transition-all duration-200">
                                    Kebijakan Pengembalian Dana
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Copyright -->
                    <div class="pt-6 border-t border-gray-200 dark:border-cyber-purple/20 text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            &copy; {{ date('Y') }} {{ $storeName }}. All rights reserved.
                        </p>
                    </div>
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
