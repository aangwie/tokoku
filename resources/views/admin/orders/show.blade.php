<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Detail Pesanan #{{ $order->order_number }}
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                &larr; Kembali ke daftar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Info Pesanan -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Ringkasan Produk -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Item Pesanan</h3>
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($order->orderItems as $item)
                                <div class="py-4 flex justify-between items-center">
                                    <div class="flex items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-md mr-4">
                                        @else
                                            <span class="inline-block h-16 w-16 rounded-md overflow-hidden bg-gray-100 dark:bg-gray-700 mr-4">
                                                <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                            </span>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $item->product ? $item->product->name : 'Produk telah dihapus' }}</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Qty: {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Perhitungan total -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="flex justify-between text-green-600 dark:text-green-400">
                                    <span>Diskon ({{ $order->coupon ? $order->coupon->code : 'Kupon' }})</span>
                                    <span>- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span>Ongkos Kirim</span>
                                <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-base font-bold text-gray-900 dark:text-white border-t border-dashed border-gray-200 dark:border-gray-700 pt-2">
                                <span>Total Pembayaran</span>
                                <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat Pengiriman -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Pengiriman</h3>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                            <p><span class="font-semibold text-gray-900 dark:text-white">Nama Pelanggan:</span> {{ $order->user->name }}</p>
                            <p><span class="font-semibold text-gray-900 dark:text-white">Email:</span> {{ $order->user->email }}</p>
                            <p><span class="font-semibold text-gray-900 dark:text-white">Alamat Lengkap:</span></p>
                            <p class="bg-gray-50 dark:bg-gray-900 p-3 rounded-md italic border border-gray-100 dark:border-gray-800">
                                {{ $order->shipping_address }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Update Status -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Status & Pembayaran</h3>

                        <div class="text-sm space-y-3 mb-6">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Gateway:</span>
                                <span class="font-semibold text-gray-900 dark:text-white uppercase">{{ $order->payment_gateway ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Referensi Bayar:</span>
                                <span class="font-mono text-gray-900 dark:text-white">{{ $order->payment_reference ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Status Saat Ini:</span>
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
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Nomor Resi:</span>
                                <span class="font-mono text-gray-900 dark:text-white">{{ $order->tracking_number ?? 'Belum di-input' }}</span>
                            </div>
                        </div>

                        <!-- Form Update Status -->
                        <form id="status-form" action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            @csrf
                            @method('PATCH')
                            
                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ubah Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                                    <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Shipping (Dikirim)</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled (Dibatalkan)</option>
                                </select>
                            </div>

                            <div class="mb-4" id="resi-group">
                                <label for="tracking_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">No Resi Pengiriman</label>
                                <input type="text" name="tracking_number" id="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" placeholder="Contoh: JNE12345" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <button type="button" onclick="confirmStatusUpdate()" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                                Perbarui Pesanan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmStatusUpdate() {
            const status = document.getElementById('status').value;
            const resi = document.getElementById('tracking_number').value;

            if (status === 'shipping' && !resi.trim()) {
                window.Swal.fire({
                    title: 'Peringatan!',
                    text: 'Harap masukkan nomor resi jika status diubah menjadi Shipping (Dikirim).',
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            window.Swal.fire({
                title: 'Konfirmasi Perubahan',
                text: "Apakah Anda yakin ingin memperbarui status pesanan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('status-form').submit();
                }
            });
        }
    </script>
</x-app-layout>
