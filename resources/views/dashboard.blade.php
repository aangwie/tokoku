<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard Pelanggan') }}
            </h2>
            <span class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                {{ now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ============================== --}}
            {{-- Welcome Greeting Hero Card --}}
            {{-- ============================== --}}
            <div class="relative overflow-hidden bg-gradient-to-r from-white via-white to-[#91ebff]/30 dark:from-gray-800 dark:to-[#91ebff]/10 rounded-3xl border border-gray-200/80 dark:border-gray-700/60 p-8 shadow-sm">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Halo, <span class="bg-gradient-to-r from-indigo-600 to-sky-500 bg-clip-text text-transparent dark:from-[#91ebff] dark:to-white">{{ Auth::user()->name }}</span>! 👋
                        </h1>
                        <p class="text-gray-600 dark:text-gray-300 text-sm max-w-xl">
                            Selamat datang di dashboard personal Anda. Melalui halaman ini, Anda dapat memantau status transaksi belanja, melacak pesanan aktif, serta melihat kupon diskon Anda.
                        </p>
                    </div>
                    <div class="shrink-0 flex gap-3">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition duration-150">
                            Mulai Belanja
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 rounded-xl border border-gray-200 dark:border-gray-600 transition duration-150">
                            Edit Profil
                        </a>
                    </div>
                </div>
                
                {{-- Decorative background glow nodes --}}
                <div class="absolute right-0 top-0 -mr-12 -mt-12 w-64 h-64 rounded-full bg-[#91ebff]/40 blur-3xl opacity-60"></div>
                <div class="absolute right-24 bottom-0 -mb-6 w-32 h-32 rounded-full bg-indigo-500/10 blur-2xl"></div>
            </div>

            {{-- ============================== --}}
            {{-- Stats Cards Grid --}}
            {{-- ============================== --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                {{-- Total Spent Card --}}
                <div class="group relative overflow-hidden bg-gradient-to-br from-white to-[#91ebff]/15 dark:from-gray-800 dark:to-gray-800/40 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-[#91ebff]/60 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-[#91ebff]/40 to-[#91ebff] dark:from-[#91ebff]/20 dark:to-[#91ebff]/60 rounded-xl shadow-sm">
                                <svg class="w-6 h-6 text-slate-800 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 bg-white/80 dark:bg-gray-700/80 px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm">Belanja</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-1">
                            Rp {{ number_format($totalSpent, 0, ',', '.') }}
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total belanja sukses terkonfirmasi</p>
                    </div>
                </div>

                {{-- Total Orders Card --}}
                <div class="group relative overflow-hidden bg-gradient-to-br from-white to-[#91ebff]/15 dark:from-gray-800 dark:to-gray-800/40 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-[#91ebff]/60 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-blue-100 to-blue-300 dark:from-blue-900/30 dark:to-blue-600 rounded-xl shadow-sm">
                                <svg class="w-6 h-6 text-blue-700 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-blue-700 dark:text-blue-300 bg-white/80 dark:bg-gray-700/80 px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm">Transaksi</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-1">
                            {{ $totalOrders }} Pesanan
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total pesanan terdaftar</p>
                    </div>
                </div>

                {{-- Active Orders Card --}}
                <div class="group relative overflow-hidden bg-gradient-to-br from-white to-[#91ebff]/15 dark:from-gray-800 dark:to-gray-800/40 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-[#91ebff]/60 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-emerald-100 to-emerald-300 dark:from-emerald-900/30 dark:to-emerald-600 rounded-xl shadow-sm">
                                <svg class="w-6 h-6 text-emerald-700 dark:text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-emerald-700 dark:text-emerald-300 bg-white/80 dark:bg-gray-700/80 px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm">Aktif</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-1">
                            {{ $activeOrders }} Dikirim / Diproses
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pesanan sedang dalam proses kirim</p>
                    </div>
                </div>

                {{-- Pending Orders Card --}}
                <div class="group relative overflow-hidden bg-gradient-to-br from-white to-[#91ebff]/15 dark:from-gray-800 dark:to-gray-800/40 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 hover:border-[#91ebff]/60 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gradient-to-br from-amber-100 to-amber-300 dark:from-amber-900/30 dark:to-amber-600 rounded-xl shadow-sm">
                                <svg class="w-6 h-6 text-amber-700 dark:text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-[10px] font-bold text-amber-700 dark:text-amber-300 bg-white/80 dark:bg-gray-700/80 px-2.5 py-1 rounded-full uppercase tracking-wider shadow-sm">Pending</span>
                        </div>
                        <h3 class="text-xl font-extrabold text-gray-900 dark:text-white mb-1">
                            {{ $pendingOrders }} Menunggu Bayar
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Silakan selesaikan pembayaran Anda</p>
                    </div>
                </div>
            </div>

            {{-- ============================== --}}
            {{-- Recent Orders Section --}}
            {{-- ============================== --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-gradient-to-br from-indigo-500 to-sky-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-850 dark:text-gray-100">Daftar Pesanan Terbaru</h3>
                    </div>
                    <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700 dark:text-[#91ebff] hover:underline flex items-center gap-1">
                        Lihat Semua Pesanan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="p-6">
                    @if($recentOrders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-gray-50/70 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">No. Pesanan</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Metode</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Total Harga</th>
                                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-750">
                                    @foreach($recentOrders as $order)
                                        <tr class="hover:bg-gray-50/40 dark:hover:bg-gray-700/20 transition-colors">
                                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                                                #{{ $order->order_number }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-650 dark:text-gray-400">
                                                {{ $order->created_at->format('d M Y, H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-gray-650 dark:text-gray-400">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                    {{ strtoupper($order->payment_gateway === 'transfer' ? 'Manual Transfer' : $order->payment_gateway) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 font-extrabold text-gray-900 dark:text-white">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @php
                                                    $statusStyle = match($order->status) {
                                                        'pending'   => 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 border border-amber-200/50 dark:border-amber-900/30',
                                                        'paid'      => 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 border border-emerald-200/50 dark:border-emerald-900/30',
                                                        'shipping'  => 'bg-blue-50 dark:bg-blue-950/20 text-blue-700 border border-blue-200/50 dark:border-blue-900/30',
                                                        'completed' => 'bg-green-50 dark:bg-green-950/20 text-green-700 border border-green-200/50 dark:border-green-900/30',
                                                        'cancelled' => 'bg-red-50 dark:bg-red-950/20 text-red-700 border border-red-200/50 dark:border-red-900/30',
                                                        default     => 'bg-gray-50 dark:bg-gray-950/20 text-gray-700 border border-gray-200/50 dark:border-gray-900/30',
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusStyle }}">
                                                    {{ ucfirst($order->status === 'paid' ? 'Lunas' : ($order->status === 'shipping' ? 'Dikirim' : ($order->status === 'completed' ? 'Selesai' : ($order->status === 'cancelled' ? 'Batal' : $order->status)))) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-1.5 px-4.5 py-2 text-xs font-bold text-indigo-600 dark:text-[#91ebff] bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-xl transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    Detail Pesanan
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="flex justify-center mb-4">
                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-650 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h4 class="text-base font-bold text-gray-700 dark:text-gray-300 mb-1">Belum Ada Transaksi Belanja</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Anda belum pernah melakukan pemesanan barang.</p>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-indigo-600 to-sky-500 hover:from-indigo-700 hover:to-sky-600 text-white font-semibold rounded-xl shadow-sm transition duration-150">
                                Mulai Jelajahi Produk
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
