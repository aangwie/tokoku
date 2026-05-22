<x-app-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Back Link --}}
        <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Riwayat Pesanan
        </a>

        {{-- Page Title --}}
        <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white mb-8">
            Detail Pesanan <span class="text-indigo-600 dark:text-indigo-400">#{{ $order->order_number }}</span>
        </h1>

        <div class="space-y-6">

            {{-- ============ Order Info Card ============ --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- Status --}}
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Status</span>
                        @php
                            $statusConfig = match($order->status) {
                                'pending'   => ['label' => 'Pending',   'classes' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400'],
                                'paid'      => ['label' => 'Paid',      'classes' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400'],
                                'shipping'  => ['label' => 'Shipping',  'classes' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400'],
                                'completed' => ['label' => 'Completed', 'classes' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400'],
                                'cancelled' => ['label' => 'Cancelled', 'classes' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'],
                                default     => ['label' => ucfirst($order->status), 'classes' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'],
                            };
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $statusConfig['classes'] }}">
                            {{ $statusConfig['label'] }}
                        </span>
                    </div>

                    {{-- Date --}}
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Tanggal Pesanan</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>

                    {{-- Payment Gateway --}}
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Metode Pembayaran</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white capitalize">{{ $order->payment_gateway ?? '-' }}</span>
                    </div>

                    {{-- Tracking Number --}}
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">No. Resi</span>
                        @if($order->tracking_number)
                            <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ $order->tracking_number }}</span>
                        @else
                            <span class="text-sm text-gray-400 dark:text-gray-500 italic">Belum tersedia</span>
                        @endif
                    </div>
                </div>

                {{-- Payment Action for Pending Orders --}}
                @if($order->status === 'pending')
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        @if($order->payment_gateway === 'transfer')
                            {{-- Bank Transfer Details Card --}}
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="p-2.5 bg-blue-600 rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Instruksi Pembayaran Transfer Bank</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Silakan transfer ke salah satu rekening berikut</p>
                                    </div>
                                </div>

                                @if(count($bankAccounts) > 0)
                                    <div class="space-y-3 mb-4">
                                        @foreach($bankAccounts as $idx => $bank)
                                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-blue-100 dark:border-blue-900 p-5">
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                                                    <div>
                                                        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Bank</span>
                                                        <span class="font-bold text-gray-900 dark:text-white text-lg">{{ $bank['bank_name'] }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">No. Rekening</span>
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-indigo-600 dark:text-indigo-400 text-lg tracking-wider bank-account-number">{{ $bank['account_number'] }}</span>
                                                            <button type="button" onclick="copyText('{{ $bank['account_number'] }}')" class="p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition" title="Salin nomor rekening">
                                                                <svg class="w-4 h-4 text-gray-400 hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Atas Nama</span>
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ $bank['account_holder'] }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Jumlah Transfer</span>
                                                        <span class="font-extrabold text-green-600 dark:text-green-400 text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- Fallback if no bank accounts configured --}}
                                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-blue-100 dark:border-blue-900 p-5 mb-4">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Rekening bank belum dikonfigurasi oleh admin. Silakan hubungi admin untuk informasi pembayaran.</p>
                                        <div class="mt-3 text-center">
                                            <span class="block text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-1">Jumlah Transfer</span>
                                            <span class="font-extrabold text-green-600 dark:text-green-400 text-lg">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 rounded-lg p-4 mb-4 text-sm">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <div class="text-amber-800 dark:text-amber-300">
                                            <p class="font-semibold mb-1">Penting:</p>
                                            <ul class="list-disc list-inside space-y-0.5 text-xs text-amber-700 dark:text-amber-400">
                                                <li>Transfer sesuai nominal yang tertera (termasuk digit terakhir)</li>
                                                <li>Setelah transfer, konfirmasi pembayaran via tombol WhatsApp di bawah</li>
                                                <li>Pesanan akan diproses setelah admin memverifikasi pembayaran</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $storeName = \App\Models\Setting::get('store_name', config('app.name', 'Toko Online'));
                                    $waNumber = '6281234567890';
                                    $firstBank = count($bankAccounts) > 0 ? $bankAccounts[0] : null;
                                    $bankInfo = $firstBank ? ($firstBank['bank_name'] . ' ' . $firstBank['account_number']) : 'rekening toko';
                                    $waMessage = urlencode("Halo Admin {$storeName},\n\nSaya ingin konfirmasi pembayaran untuk:\n📦 No. Pesanan: {$order->order_number}\n💰 Total: Rp " . number_format($order->total_price, 0, ',', '.') . "\n\nSudah saya transfer ke {$bankInfo}.\nMohon diproses. Terima kasih! 🙏");
                                @endphp

                                <a href="https://wa.me/{{ $waNumber }}?text={{ $waMessage }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 text-sm">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    Konfirmasi via WhatsApp
                                </a>
                            </div>
                        @elseif($order->payment_reference)
                            @if($order->payment_gateway === 'midtrans')
                                <button type="button" id="pay-midtrans-btn"
                                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-sm transition duration-150 text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    Bayar Sekarang
                                </button>
                            @elseif($order->payment_gateway === 'xendit')
                                <a href="{{ $order->payment_reference }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-2 px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold rounded-lg shadow-sm transition duration-150 text-sm">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Bayar via Xendit
                                </a>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            {{-- ============ Order Items ============ --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Item Pesanan</h2>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden md:block">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Produk</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Harga</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Jumlah</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name ?? 'Product' }}"
                                                     class="w-14 h-14 rounded-lg object-cover border border-gray-200 dark:border-gray-600 shrink-0">
                                            @else
                                                <div class="w-14 h-14 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                                                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M21.9 2.19a1 1 0 0 0-1.4 0L1.7 20.9A1 1 0 0 0 3.1 22.3l18.8-18.7a1 1 0 0 0 0-1.4z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                            <span class="font-medium text-gray-900 dark:text-white">
                                                {{ $item->product->name ?? 'Produk Dihapus' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-gray-600 dark:text-gray-400">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="md:hidden divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach($order->orderItems as $item)
                        <div class="p-4 flex items-center gap-4">
                            @if($item->product && $item->product->image)
                                <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name ?? 'Product' }}"
                                     class="w-16 h-16 rounded-lg object-cover border border-gray-200 dark:border-gray-600 shrink-0">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center shrink-0">
                                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21.9 2.19a1 1 0 0 0-1.4 0L1.7 20.9A1 1 0 0 0 3.1 22.3l18.8-18.7a1 1 0 0 0 0-1.4z"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900 dark:text-white truncate">{{ $item->product->name ?? 'Produk Dihapus' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $item->quantity }}x @ Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                            </div>
                            <span class="font-bold text-gray-900 dark:text-white text-sm whitespace-nowrap">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- ============ Price Summary ============ --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Ringkasan Harga</h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>Subtotal</span>
                            <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-green-600 dark:text-green-400">
                                <span>Diskon</span>
                                <span class="font-medium">- Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-gray-600 dark:text-gray-400">
                            <span>Ongkos Kirim</span>
                            <span class="font-medium text-gray-900 dark:text-white">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 pt-3 flex justify-between">
                            <span class="text-base font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-base font-extrabold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                {{-- ============ Shipping Address ============ --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Alamat Pengiriman</h2>
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 shrink-0">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Midtrans Snap JS (only for pending midtrans orders) --}}
    @if($order->status === 'pending' && $order->payment_gateway === 'midtrans' && $order->payment_reference)
        @php
            $midtransIsProduction = \App\Models\Setting::get('midtrans_is_production', '0');
            $snapUrl = ($midtransIsProduction === '1')
                ? 'https://app.midtrans.com/snap/snap.js'
                : 'https://app.sandbox.midtrans.com/snap/snap.js';
        @endphp
        <script src="{{ $snapUrl }}" data-client-key="{{ $midtransClientKey }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const payBtn = document.getElementById('pay-midtrans-btn');
                if (payBtn) {
                    payBtn.addEventListener('click', function () {
                        snap.pay('{{ $order->payment_reference }}', {
                            onSuccess: function (result) {
                                window.Swal.fire({
                                    title: 'Pembayaran Berhasil!',
                                    text: 'Terima kasih, pembayaran Anda telah diterima.',
                                    icon: 'success',
                                    confirmButtonColor: '#4f46e5',
                                }).then(() => {
                                    window.location.reload();
                                });
                            },
                            onPending: function (result) {
                                window.Swal.fire({
                                    title: 'Menunggu Pembayaran',
                                    text: 'Silakan selesaikan pembayaran Anda.',
                                    icon: 'info',
                                    confirmButtonColor: '#4f46e5',
                                });
                            },
                            onError: function (result) {
                                window.Swal.fire({
                                    title: 'Pembayaran Gagal',
                                    text: 'Terjadi kesalahan saat memproses pembayaran.',
                                    icon: 'error',
                                    confirmButtonColor: '#ef4444',
                                });
                            },
                            onClose: function () {
                                window.Swal.fire({
                                    title: 'Pembayaran Dibatalkan',
                                    text: 'Anda menutup popup pembayaran. Silakan coba lagi.',
                                    icon: 'warning',
                                    confirmButtonColor: '#4f46e5',
                                });
                            }
                        });
                    });
                }
            });
        </script>
    @endif

    <script>
        function copyText(text) {
            navigator.clipboard.writeText(text).then(() => {
                if (window.Swal) {
                    window.Swal.fire({
                        title: 'Tersalin!',
                        text: 'Nomor rekening berhasil disalin ke clipboard.',
                        icon: 'success',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                } else {
                    alert('Nomor rekening berhasil disalin!');
                }
            }).catch(err => {
                console.error('Gagal menyalin nomor rekening:', err);
            });
        }
    </script>
</x-app-layout>
