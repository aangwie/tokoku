@php
    $storeName = \App\Models\Setting::get('store_name', config('app.name', 'BN Boutique'));
@endphp

<!-- Desktop Sidebar -->
<aside :class="[sidebarOpen ? 'lg:flex' : 'hidden lg:hidden', sidebarMinimized ? 'lg:w-20' : 'lg:w-64']" class="lg:flex-col lg:fixed lg:inset-y-0 lg:z-30 bg-slate-900 border-r border-[#91ebff]/10 text-slate-300 transition-all duration-300 ease-in-out">
    <!-- Sidebar Header -->
    <div class="flex items-center border-b border-slate-800 shrink-0 h-16 transition-all duration-300" :class="sidebarMinimized ? 'justify-center px-2' : 'px-6 gap-3'">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <x-application-logo class="h-9 w-auto object-contain rounded-md shrink-0" />
            <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="font-extrabold text-white text-lg tracking-wider truncate">{{ $storeName }}</span>
        </a>
    </div>

    <!-- Sidebar Links -->
    <div class="flex-grow overflow-y-auto px-4 py-6 space-y-1.5 scrollbar-none">
        <!-- Storefront Link -->
        <a href="{{ route('home') }}" class="flex items-center px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-slate-800/60 hover:text-white transition duration-150" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Lihat Toko</span>
        </a>

        <div class="h-px bg-slate-800/60 my-4"></div>

        <!-- Dashboard Link -->
        <a href="{{ route('dashboard') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11V20a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Dashboard</span>
        </a>

        @if(Auth::user()->role === 'admin')
            <div class="pt-4 pb-2" x-show="!sidebarMinimized">
                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Menu Admin</p>
            </div>

            <!-- Admin Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Analisis Penjualan</span>
            </a>

            <!-- Kategori -->
            <a href="{{ route('admin.categories.index') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kategori</span>
            </a>

            <!-- Produk -->
            <a href="{{ route('admin.products.index') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Produk</span>
            </a>

            <!-- Kupon -->
            <a href="{{ route('admin.coupons.index') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.coupons.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Kupon</span>
            </a>

            <!-- Pesanan -->
            <a href="{{ route('admin.orders.index') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.orders.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pesanan</span>
            </a>

            <!-- Pengaturan -->
            <a href="{{ route('admin.settings.edit') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pengaturan Toko</span>
            </a>
        @else
            <div class="pt-4 pb-2" x-show="!sidebarMinimized">
                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Menu Pelanggan</p>
            </div>

            <!-- Pesanan Saya -->
            <a href="{{ route('orders.index') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('orders.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pesanan Saya</span>
            </a>

            <!-- Pengaturan Pelanggan -->
            <a href="{{ route('customer.settings') }}" class="flex items-center py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('customer.settings') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Pengaturan</span>
            </a>
        @endif

        <!-- Collapse Toggle Button at the bottom of standard list -->
        <div class="pt-4 border-t border-slate-800/60">
            <button @click="sidebarMinimized = !sidebarMinimized; localStorage.setItem('sidebarMinimized', sidebarMinimized)" class="w-full flex items-center px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-slate-800/60 hover:text-white transition duration-150" :class="sidebarMinimized ? 'justify-center' : 'gap-3'">
                <!-- Toggle Chevron Icons -->
                <svg x-show="!sidebarMinimized" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <svg x-show="sidebarMinimized" class="w-5 h-5 shrink-0" style="display: none;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
                <span x-show="!sidebarMinimized" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">Sembunyikan Menu</span>
            </button>
        </div>
    </div>

    <!-- Sidebar Footer (Profile Info & Logout) -->
    <div class="p-4 border-t border-slate-800 bg-slate-950/40 transition-all duration-300">
        <div class="flex items-center justify-between" :class="sidebarMinimized ? 'flex-col gap-3 justify-center' : ''">
            <div x-show="!sidebarMinimized" class="min-w-0" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <a href="{{ route('profile.edit') }}" class="block font-semibold text-white text-sm hover:underline truncate" title="{{ Auth::user()->name }}">
                    {{ Auth::user()->name }}
                </a>
                <span class="block text-xs text-slate-500 truncate" title="{{ Auth::user()->email }}">
                    {{ Auth::user()->email }}
                </span>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                @csrf
                <button type="submit" class="p-2 rounded-lg text-slate-500 hover:bg-slate-800 hover:text-red-400 transition duration-150" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Drawer (Overlay) -->
<div class="lg:hidden">
    <div x-show="sidebarOpen" class="fixed inset-0 z-40" style="display: none;">
    <!-- Backdrop overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="transition-opacity ease-linear duration-300" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-slate-900 bg-opacity-75"
         @click="sidebarOpen = false"></div>

    <!-- Drawer panel -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-in-out duration-300 transform" 
         x-transition:enter-start="-translate-x-full" 
         x-transition:enter-end="translate-x-0" 
         x-transition:leave="transition ease-in-out duration-300 transform" 
         x-transition:leave-start="translate-x-0" 
         x-transition:leave-end="-translate-x-full" 
         class="fixed inset-y-0 left-0 flex flex-col z-50 w-64 bg-slate-900 border-r border-slate-800 text-slate-300">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 h-16 border-b border-slate-800">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <x-application-logo class="h-9 w-auto object-contain rounded-md shrink-0" />
                <span class="font-extrabold text-white text-lg tracking-wider truncate">{{ $storeName }}</span>
            </a>
            <button @click="sidebarOpen = false" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-800 hover:text-white focus:outline-none">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Links -->
        <div class="flex-grow overflow-y-auto px-4 py-6 space-y-1.5 scrollbar-none">
            <!-- Storefront -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:bg-slate-800/60 hover:text-white transition duration-150">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Lihat Toko
            </a>

            <div class="h-px bg-slate-800/60 my-4"></div>

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11V20a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>

            @if(Auth::user()->role === 'admin')
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Menu Admin</p>
                </div>

                <!-- Admin Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analisis Penjualan
                </a>

                <!-- Kategori -->
                <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.categories.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    Kategori
                </a>

                <!-- Produk -->
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.products.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-14L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    Produk
                </a>

                <!-- Kupon -->
                <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.coupons.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                    Kupon
                </a>

                <!-- Pesanan -->
                <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.orders.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Pesanan
                </a>

                <!-- Pengaturan -->
                <a href="{{ route('admin.settings.edit') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('admin.settings.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan Toko
                </a>
            @else
                <div class="pt-4 pb-2">
                    <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Menu Pelanggan</p>
                </div>

                <!-- Pesanan Saya -->
                <a href="{{ route('orders.index') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('orders.*') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Pesanan Saya
                </a>

                <!-- Pengaturan Pelanggan -->
                <a href="{{ route('customer.settings') }}" class="flex items-center gap-3 py-2.5 rounded-xl text-sm font-medium transition duration-150 {{ request()->routeIs('customer.settings') ? 'bg-[#91ebff]/10 text-white font-bold border-l-2 border-[#91ebff] pl-3.5 pr-4' : 'text-slate-400 hover:bg-slate-800/60 hover:text-white px-4' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Pengaturan
                </a>
            @endif
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/40">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <a href="{{ route('profile.edit') }}" class="block font-semibold text-white text-sm hover:underline truncate" title="{{ Auth::user()->name }}">
                        {{ Auth::user()->name }}
                    </a>
                    <span class="block text-xs text-slate-500 truncate" title="{{ Auth::user()->email }}">
                        {{ Auth::user()->email }}
                    </span>
                </div>
                
                <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                    @csrf
                    <button type="submit" class="p-2 rounded-lg text-slate-500 hover:bg-slate-800 hover:text-red-400 transition duration-150" title="Keluar">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
