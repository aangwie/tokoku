@php
    $storeName = \App\Models\Setting::get('store_name', config('app.name', 'BN Boutique'));
@endphp

<!-- Desktop Sidebar -->
<aside :class="[sidebarOpen ? 'lg:flex' : 'hidden lg:hidden', sidebarMinimized ? 'lg:w-20' : 'lg:w-64']" class="lg:flex-col lg:fixed lg:inset-y-0 lg:z-30 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 transition-all duration-300">
    <!-- Header -->
    <div class="flex items-center border-b border-gray-200 dark:border-gray-700 h-16 px-6 shrink-0" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
        <x-application-logo class="h-9 w-auto object-contain rounded-md shrink-0" />
        <span x-show="!sidebarMinimized" class="font-bold text-gray-900 dark:text-white text-lg truncate">{{ $storeName }}</span>
    </div>

    <!-- Links -->
    <div class="flex-grow overflow-y-auto px-4 py-6 space-y-1.5">
        <!-- Lihat Toko -->
        <a href="{{ route('home') }}" class="flex items-center px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white" :class="sidebarMinimized ? 'justify-center' : 'gap-3'" target="_blank">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            <span x-show="!sidebarMinimized">Lihat Toko</span>
        </a>

        <div class="h-px bg-gray-200 dark:bg-gray-700 my-3"></div>

        @if(Auth::user()->role === 'admin')
            <!-- Dashboard Admin - Analisis Penjualan -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11V20a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span x-show="!sidebarMinimized">Dashboard</span>
            </a>
            <div class="pt-4 pb-2" x-show="!sidebarMinimized">
                <p class="px-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Menu Admin</p>
            </div>

            <!-- Kategori -->
            <a href="{{ route('admin.categories.index') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                <span x-show="!sidebarMinimized">Kategori</span>
            </a>

            <!-- Produk -->
            <a href="{{ route('admin.products.index') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.products.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span x-show="!sidebarMinimized">Produk</span>
            </a>

            <!-- Kupon -->
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.coupons.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                <span x-show="!sidebarMinimized">Kupon</span>
            </a>

            <!-- Pesanan -->
            <a href="{{ route('admin.orders.index') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.orders.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span x-show="!sidebarMinimized">Pesanan</span>
            </a>

            <!-- Pengaturan Toko -->
            <a href="{{ route('admin.settings.edit') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.settings.*') && !request()->routeIs('admin.settings.profile.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span x-show="!sidebarMinimized">Pengaturan Toko</span>
            </a>

            <!-- Profile Admin -->
            <a href="{{ route('admin.settings.profile.edit') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.settings.profile.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span x-show="!sidebarMinimized">Profile Admin</span>
            </a>

            <!-- Update Sistem -->
            <a href="{{ route('admin.system.index') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.system.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span x-show="!sidebarMinimized">Update Sistem</span>
            </a>
        @else
            <!-- Dashboard Pelanggan -->
            <a href="{{ route('dashboard') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11V20a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span x-show="!sidebarMinimized">Dashboard</span>
            </a>
            <div class="pt-4 pb-2" x-show="!sidebarMinimized">
                <p class="px-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Menu Pelanggan</p>
            </div>

            <!-- Pesanan Saya -->
            <a href="{{ route('orders.index') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('orders.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span x-show="!sidebarMinimized">Pesanan Saya</span>
            </a>

            <!-- Pengaturan Pelanggan -->
            <a href="{{ route('customer.settings') }}" class="flex items-center py-2.5 rounded-lg text-sm {{ request()->routeIs('customer.settings') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span x-show="!sidebarMinimized">Pengaturan</span>
            </a>
        @endif

        <!-- Collapse Button -->
        <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
            <button @click="sidebarMinimized = !sidebarMinimized; localStorage.setItem('sidebarMinimized', sidebarMinimized)" class="w-full flex items-center px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg x-show="!sidebarMinimized" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/></svg>
                <svg x-show="sidebarMinimized" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/></svg>
                <span x-show="!sidebarMinimized">Sembunyikan Menu</span>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4 shrink-0">
        <div class="flex items-center justify-between gap-3" :class="sidebarMinimized ? 'flex-col' : ''">
            <div class="flex-1 min-w-0" x-show="!sidebarMinimized">
                <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-red-400 dark:hover:text-red-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar -->
<div class="lg:hidden">
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50"></div>
    <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 overflow-y-auto flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 h-16 px-6 shrink-0">
            <div class="flex items-center gap-3">
                <x-application-logo class="h-9 w-auto object-contain rounded-md" />
                <span class="font-bold text-white text-lg">{{ $storeName }}</span>
            </div>
            <button @click="sidebarOpen = false" class="p-2 rounded-lg text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Links -->
        <div class="flex-grow overflow-y-auto px-4 py-6 space-y-1.5">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Lihat Toko</span>
            </a>

            <div class="h-px bg-gray-200 dark:bg-gray-700 my-3"></div>

            @if(Auth::user()->role === 'admin')
                <!-- Dashboard Admin -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11V20a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Menu Admin</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.categories.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Kategori</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.products.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Produk</span>
                </a>
                <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.coupons.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    <span>Kupon</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.orders.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Pesanan</span>
                </a>
                <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.settings.*') && !request()->routeIs('admin.settings.profile.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan Toko</span>
                </a>
                <a href="{{ route('admin.settings.profile.edit') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.settings.profile.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Profile Admin</span>
                </a>
                <a href="{{ route('admin.system.index') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('admin.system.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Update Sistem</span>
                </a>
            @else
                <!-- Dashboard Pelanggan -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('dashboard') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11V20a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Menu Pelanggan</p>
                </div>
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('orders.*') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Pesanan Saya</span>
                </a>
                <a href="{{ route('customer.settings') }}" class="flex items-center gap-3 py-2.5 rounded-lg text-sm {{ request()->routeIs('customer.settings') ? 'bg-cyan-500/10 text-cyan-400 font-bold border-l-2 border-cyan-400 pl-3.5 pr-4' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Pengaturan</span>
                </a>
            @endif
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-4 shrink-0">
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-red-400">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</div>
