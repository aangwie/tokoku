<x-customer-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">💳 Checkout</h1>

        <form id="checkout-form" action="{{ route('cart.checkout.process') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Shipping Address --}}
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6" x-data="addressSelector()">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Alamat Pengiriman
                        </h2>

                        {{-- Saved Addresses --}}
                        @php
                            $savedAddresses = \App\Models\CustomerAddress::where('user_id', auth()->id())
                                ->orderBy('is_default', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp

                        @if($savedAddresses->count() > 0)
                            <div class="space-y-2 mb-4">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Pilih alamat tersimpan</p>
                                @foreach($savedAddresses as $sa)
                                    <label class="block p-3 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-indigo-300"
                                           :class="selectedAddress === {{ $sa->id }} ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/20' : 'border-gray-200 dark:border-gray-700'">
                                        <div class="flex items-start gap-3">
                                            <input type="radio" name="address_choice" value="{{ $sa->id }}"
                                                   @click="selectSaved({{ $sa->id }}, {{ json_encode($sa->recipient_name . ' (' . $sa->phone . ")\n" . $sa->full_address) }})"
                                                   class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                                   {{ $sa->is_default ? 'checked' : '' }}>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $sa->label }}</span>
                                                    @if($sa->is_default)
                                                        <span class="text-[10px] px-1.5 py-0.5 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 font-bold rounded">Utama</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">{{ $sa->recipient_name }} · {{ $sa->phone }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2">{{ $sa->full_address }}</p>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        @endif

                        <textarea name="shipping_address" rows="4" required x-ref="addressInput" readonly
                            placeholder="Pilih alamat pengiriman dari daftar di atas"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm resize-none bg-gray-50 dark:bg-gray-800">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Payment Gateway Selection --}}
                    @php
                        $paymentMethodSetting = \App\Models\Setting::get('payment_method', 'paymentgateway');
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Metode Pembayaran
                        </h2>
                        <div class="space-y-3">
                            @if($paymentMethodSetting === 'transfer')
                                {{-- Transfer Bank Manual --}}
                                <label class="flex items-start gap-4 p-4 border-2 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl cursor-pointer">
                                    <input type="radio" name="payment_gateway" value="transfer" checked class="mt-1 text-indigo-600 focus:ring-indigo-500">
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">🏦</span>
                                            <span class="font-bold text-gray-900 dark:text-white">Transfer Bank Manual</span>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pembayaran manual via transfer bank (BCA). Rekening bank dan petunjuk pembayaran akan ditampilkan setelah pesanan dibuat.</p>
                                    </div>
                                </label>
                            @else
                                {{-- Midtrans --}}
                                <label class="flex items-start gap-4 p-4 border-2 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl cursor-pointer">
                                    <input type="radio" name="payment_gateway" value="midtrans" class="mt-1 text-indigo-600 focus:ring-indigo-500" checked>
                                    <div class="flex-grow">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">🏦</span>
                                            <span class="font-bold text-gray-900 dark:text-white">Midtrans</span>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bayar via GoPay, QRIS, Bank Transfer, Kartu Kredit</p>
                                    </div>
                                </label>
                            @endif
                        </div>
                        @error('payment_gateway')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Order Recap --}}
                <div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            Ringkasan Belanja
                        </h2>

                        @php
                            $cart = session('cart', []);
                            $subtotal = 0;
                            $totalWeight = 0;
                            foreach ($cart as $item) {
                                $subtotal += $item['price'] * $item['quantity'];
                                $totalWeight += $item['weight'] * $item['quantity'];
                            }
                            $shippingCost = count($cart) > 0 ? max(15000, ceil($totalWeight / 1000) * 15000) : 0;
                            $discountAmount = 0;
                            $coupon = session('coupon');
                            if ($coupon && $subtotal >= $coupon['min_order']) {
                                $discountAmount = $coupon['type'] === 'percentage' ? ($subtotal * $coupon['value']) / 100 : $coupon['value'];
                                $discountAmount = min($discountAmount, $subtotal);
                            }
                            $totalPrice = $subtotal - $discountAmount + $shippingCost;
                        @endphp

                        {{-- Items List --}}
                        <div class="divide-y divide-gray-200 dark:divide-gray-700 mb-4 max-h-60 overflow-y-auto">
                            @foreach($cart as $id => $item)
                                <div class="py-3 flex justify-between items-center text-sm">
                                    <div class="flex-grow">
                                        <p class="text-gray-900 dark:text-white font-medium truncate">{{ $item['name'] }}</p>
                                        <p class="text-gray-500 dark:text-gray-400">{{ $item['quantity'] }}x @ Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <span class="font-semibold text-gray-900 dark:text-white ml-4">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Price Summary --}}
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2 text-sm">
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Subtotal</span>
                                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            @if($discountAmount > 0)
                                <div class="flex justify-between text-green-600 dark:text-green-400">
                                    <span>Diskon ({{ $coupon['code'] }})</span>
                                    <span>- Rp {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                <span>Ongkos Kirim</span>
                                <span>Rp {{ number_format($shippingCost, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between text-xl font-bold text-gray-900 dark:text-white">
                                <span>Total Bayar</span>
                                <span class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        {{-- Pay Button --}}
                        <button type="button" onclick="confirmCheckout()"
                            class="mt-6 w-full inline-flex justify-center items-center px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-lg rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Bayar Sekarang
                        </button>

                        <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-3">
                            Dengan melanjutkan, Anda menyetujui syarat dan ketentuan kami.
                        </p>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function addressSelector() {
            return {
                selectedAddress: null,
                init() {
                    const checkedRadio = this.$el.querySelector('input[name="address_choice"]:checked');
                    if (checkedRadio) {
                        const val = checkedRadio.value;
                        this.selectedAddress = parseInt(val);
                        @if($savedAddresses->count() > 0)
                            const defaultAddr = @json($savedAddresses->firstWhere('is_default', true) ?? $savedAddresses->first());
                            if (defaultAddr && defaultAddr.id === this.selectedAddress) {
                                this.$refs.addressInput.value = defaultAddr.recipient_name + ' (' + defaultAddr.phone + ')\n' + defaultAddr.full_address;
                                this.$refs.addressInput.readOnly = true;
                            }
                        @endif
                    }
                },
                selectSaved(id, text) {
                    this.selectedAddress = id;
                    this.$refs.addressInput.value = text;
                    this.$refs.addressInput.readOnly = true;
                }
            };
        }

        function confirmCheckout() {
            const form = document.getElementById('checkout-form');
            const address = form.querySelector('textarea[name="shipping_address"]');
            const gateway = form.querySelector('input[name="payment_gateway"]:checked');

            if (!address.value.trim()) {
                window.Swal.fire({
                    title: 'Alamat Kosong',
                    text: 'Harap isi alamat pengiriman terlebih dahulu.',
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            if (!gateway) {
                window.Swal.fire({
                    title: 'Metode Pembayaran',
                    text: 'Harap pilih metode pembayaran.',
                    icon: 'warning',
                    confirmButtonColor: '#4f46e5'
                });
                return;
            }

            const gatewayNames = { midtrans: 'Midtrans', transfer: 'Transfer Bank Manual (BCA)' };
            const gatewayName = gatewayNames[gateway.value] || gateway.value;

            window.Swal.fire({
                title: 'Konfirmasi Pesanan',
                html: 'Anda akan melakukan pembayaran via <b>' + gatewayName + '</b>.<br>Apakah Anda yakin ingin melanjutkan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Bayar Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</x-customer-layout>
