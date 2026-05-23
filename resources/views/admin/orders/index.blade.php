<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Pesanan Masuk') }}
        </h2>
    </x-slot>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.tailwindcss.min.css">

    <style>
        /* Dark mode styles for DataTables */
        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: #e5e7eb;
        }

        .dark .dataTables_wrapper .dataTables_filter input,
        .dark .dataTables_wrapper .dataTables_length select {
            background-color: #374151;
            border-color: #4b5563;
            color: #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.5rem;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #e5e7eb !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #374151 !important;
            border-color: #4b5563 !important;
            color: #fff !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5 !important;
            border-color: #4f46e5 !important;
            color: #fff !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #6b7280 !important;
        }

        /* Custom styling for better integration */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
            margin: 0 0.5rem;
        }

        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
        }

        .dataTables_wrapper .dataTables_info {
            padding-top: 1rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Revenue Statistics Card --}}
            <div class="mb-6 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold mb-2">💰 Total Pendapatan</h3>
                        <p class="text-3xl font-bold">Rp {{ number_format($filteredRevenue, 0, ',', '.') }}</p>
                        <p class="text-sm opacity-90 mt-1">{{ $filterLabel }}</p>
                    </div>
                    
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.orders.index', ['filter_type' => 'all']) }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition {{ $filterType === 'all' ? 'bg-white text-indigo-600' : 'bg-white/20 hover:bg-white/30' }}">
                            Semua Waktu
                        </a>
                        <a href="{{ route('admin.orders.index', ['filter_type' => 'month']) }}" 
                           class="px-4 py-2 rounded-lg font-semibold transition {{ $filterType === 'month' ? 'bg-white text-indigo-600' : 'bg-white/20 hover:bg-white/30' }}">
                            Bulan Ini
                        </a>
                        <button onclick="toggleCustomFilter()" 
                                class="px-4 py-2 rounded-lg font-semibold transition {{ $filterType === 'custom' ? 'bg-white text-indigo-600' : 'bg-white/20 hover:bg-white/30' }}">
                            Custom
                        </button>
                    </div>
                </div>
                
                {{-- Custom Date Range Filter --}}
                <div id="customFilterForm" class="mt-4 pt-4 border-t border-white/20 {{ $filterType === 'custom' ? '' : 'hidden' }}">
                    <form method="GET" action="{{ route('admin.orders.index') }}" class="flex flex-wrap gap-3 items-end">
                        <input type="hidden" name="filter_type" value="custom">
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" 
                                   class="px-3 py-2 rounded-lg border-0 text-gray-900 focus:ring-2 focus:ring-white" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" 
                                   class="px-3 py-2 rounded-lg border-0 text-gray-900 focus:ring-2 focus:ring-white" required>
                        </div>
                        <button type="submit" class="px-6 py-2 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition">
                            Terapkan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Order Statistics Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
                <div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-lg shadow-lg p-4 text-white">
                    <p class="text-sm opacity-90">Total Pesanan</p>
                    <p class="text-2xl font-bold">{{ $orderCounts['total'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-lg shadow-lg p-4 text-white">
                    <p class="text-sm opacity-90">Pending</p>
                    <p class="text-2xl font-bold">{{ $orderCounts['pending'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-green-400 to-green-500 rounded-lg shadow-lg p-4 text-white">
                    <p class="text-sm opacity-90">Paid</p>
                    <p class="text-2xl font-bold">{{ $orderCounts['paid'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-400 to-indigo-500 rounded-lg shadow-lg p-4 text-white">
                    <p class="text-sm opacity-90">Shipping</p>
                    <p class="text-2xl font-bold">{{ $orderCounts['shipping'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg shadow-lg p-4 text-white">
                    <p class="text-sm opacity-90">Completed</p>
                    <p class="text-2xl font-bold">{{ $orderCounts['completed'] }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-400 to-red-500 rounded-lg shadow-lg p-4 text-white">
                    <p class="text-sm opacity-90">Cancelled</p>
                    <p class="text-2xl font-bold">{{ $orderCounts['cancelled'] }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table id="ordersTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No Pesanan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pelanggan</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Metode</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($orders as $order)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">#{{ $order->order_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $order->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $order->created_at->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 uppercase">
                                        {{ $order->payment_gateway ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @switch($order->status)
                                            @case('pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                                                @break
                                            @case('paid')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Paid</span>
                                                @break
                                            @case('shipping')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">Shipping</span>
                                                @break
                                            @case('completed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Completed</span>
                                                @break
                                            @case('cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Cancelled</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500 dark:text-gray-400">Belum ada pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.tailwindcss.min.js"></script>

    <script>
        // Toggle custom filter form
        function toggleCustomFilter() {
            const form = document.getElementById('customFilterForm');
            form.classList.toggle('hidden');
        }

        $(document).ready(function() {
            $('#ordersTable').DataTable({
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 10,
                "ordering": true,
                "order": [[2, 'desc']], // Sort by date (newest first) by default
                "columnDefs": [
                    {
                        "orderable": false,
                        "targets": [5, 6] // Disable sorting on status and action columns
                    }
                ]
            });
        });
    </script>
</x-app-layout>