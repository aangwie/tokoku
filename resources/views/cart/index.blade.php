<x-customer-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">🛒 Keranjang Belanja</h1>

        @if(count($cart) === 0)
            {{-- Empty Cart State --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-2">Keranjang Anda Kosong</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Belum ada produk di keranjang belanja Anda. Ayo mulai belanja!</p>
                <a href="{{ route('home') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow-md transition duration-200 transform hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                    Mulai Belanja
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    {{-- Clear Cart Button --}}
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ count($cart) }}</span> produk di keranjang
                        </p>
                        <form id="clear-cart-form" action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmClearCart()"
                                class="inline-flex items-center px-4 py-2 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/50 font-medium rounded-lg transition-all duration-200 border border-red-200 dark:border-red-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Kosongkan Keranjang
                            </button>
                        </form>
                    </div>

                    @foreach($cart as $id => $item)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-4 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4 hover:shadow-lg transition-shadow duration-200">
                            {{-- Product Image --}}
                            <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700">
                                @if($item['image'])
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Product Info --}}
                            <div class="flex-grow min-w-0">
                                <a href="{{ route('products.show', $item['slug']) }}" class="text-lg font-semibold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate block">
                                    {{ $item['name'] }}
                                </a>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} x {{ $item['quantity'] }}
                                </p>
                                <p class="text-indigo-600 dark:text-indigo-400 font-bold mt-1">
                                    Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                                </p>
                            </div>

                            {{-- Quantity Control --}}
                            <div class="flex items-center gap-3">
                                <form action="{{ route('cart.update', $id) }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                        class="w-20 text-center rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                        onchange="this.form.submit()">
                                </form>

                                {{-- Remove Button --}}
                                <form id="remove-form-{{ $id }}" action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmRemove({{ $id }}, '{{ addslashes($item['name']) }}')"
                                        class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                            Ringkasan Pesanan
                        </h2>

                        {{-- Coupon Section --}}
                        <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                            @if($coupon)
                                <div class="flex items-center justify-between bg-green-50 dark:bg-green-900/30 p-3 rounded-lg">
                                    <div>
                                        <p class="text-sm font-semibold text-green-700 dark:text-green-400">🎫 {{ $coupon['code'] }}</p>
                                        <p class="text-xs text-green-600 dark:text-green-500">
                                            {{ $coupon['type'] === 'percentage' ? $coupon['value'].'% OFF' : 'Rp '.number_format($coupon['value'], 0, ',', '.').' OFF' }}
                                        </p>
                                    </div>
                                    <form action="{{ route('cart.coupon.remove') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium transition-colors">Hapus</button>
                                    </form>
                                </div>
                            @else
                                <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex gap-2">
                                    @csrf
                                    <input type="text" name="code" placeholder="Kode Kupon" required
                                        class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm">
                                        Terapkan
                                    </button>
                                </form>
                            @endif
                        </div>

                        {{-- Price Breakdown --}}
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($discountAmount > 0)
                                <div class="flex justify-between text-green-600 dark:text-green-400">
                                    <span>Diskon</span>
                                    <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Ongkos Kirim ({{ number_format($totalWeight, 0, ',', '.') }}g)</span>
                                <span>
                                    @if($shippingCost > 0)
                                        Rp {{ number_format($shippingCost, 0, ',', '.') }}
                                    @else
                                        <span class="text-green-600 dark:text-green-400 font-semibold">Gratis</span>
                                    @endif
                                </span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between text-lg font-bold text-gray-900 dark:text-white">
                                <span>Total</span>
                                <span class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Checkout Button --}}
                        <a href="{{ route('cart.checkout') }}"
                            class="mt-6 w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            Lanjutkan ke Checkout
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        function confirmRemove(id, name) {
            window.Swal.fire({
                title: 'Hapus Produk?',
                text: 'Apakah Anda yakin ingin menghapus "' + name + '" dari keranjang?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('remove-form-' + id).submit();
                }
            });
        }

        function confirmClearCart() {
            window.Swal.fire({
                title: 'Kosongkan Keranjang?',
                text: 'Apakah Anda yakin ingin menghapus semua produk dari keranjang belanja?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Kosongkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('clear-cart-form').submit();
                }
            });
        }
    </script>
</x-customer-layout>
