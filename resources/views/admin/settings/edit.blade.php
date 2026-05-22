<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pengaturan Toko') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-8">
                    {{-- Section Header --}}
                    <div class="flex items-center gap-3 mb-8 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Konfigurasi Umum</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Atur nama dan logo toko Anda</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Store Name --}}
                        <div class="mb-8">
                            <label for="store_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Nama Toko
                            </label>
                            <input
                                type="text"
                                name="store_name"
                                id="store_name"
                                value="{{ old('store_name', $settings['store_name']) }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 px-4 py-3"
                                placeholder="Masukkan nama toko..."
                                required
                            />
                            @error('store_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Payment Method --}}
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                Metode Pembayaran Toko
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Payment Gateway Option --}}
                                <label class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-indigo-400 dark:hover:border-indigo-500 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 dark:has-[:checked]:bg-indigo-950/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">💳</span>
                                            <span class="font-bold text-gray-900 dark:text-white">Payment Gateway</span>
                                        </div>
                                        <input type="radio" name="payment_method" value="paymentgateway" class="text-indigo-600 focus:ring-indigo-500" 
                                            {{ $settings['payment_method'] === 'paymentgateway' ? 'checked' : '' }}>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Pembayaran otomatis menggunakan Midtrans dan Xendit. Pelanggan dapat membayar via Virtual Account, QRIS, E-Wallet, dll.</p>
                                </label>

                                {{-- Manual Bank Transfer Option --}}
                                <label class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-indigo-400 dark:hover:border-indigo-500 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/50 dark:has-[:checked]:bg-indigo-950/20">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">🏦</span>
                                            <span class="font-bold text-gray-900 dark:text-white">Transfer Bank Manual</span>
                                        </div>
                                        <input type="radio" name="payment_method" value="transfer" class="text-indigo-600 focus:ring-indigo-500" 
                                            {{ $settings['payment_method'] === 'transfer' ? 'checked' : '' }}>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Pembayaran manual via transfer bank. Toko akan menampilkan rekening bank (BCA) pada halaman detail pesanan pelanggan.</p>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Store Logo --}}
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Logo Toko
                            </label>

                            {{-- Current Logo Preview --}}
                            @if($settings['store_logo'])
                                <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-gray-200 dark:border-gray-600 inline-block">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 font-medium uppercase tracking-wider">Logo Saat Ini</p>
                                    <img
                                        src="{{ asset($settings['store_logo']) }}"
                                        alt="Logo Toko"
                                        class="h-20 w-auto object-contain rounded-lg shadow-sm"
                                    />
                                </div>
                            @else
                                <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-600 inline-block">
                                    <p class="text-sm text-gray-400 dark:text-gray-500 italic">Belum ada logo</p>
                                </div>
                            @endif

                            {{-- Upload Input --}}
                            <div class="relative">
                                <input
                                    type="file"
                                    name="store_logo"
                                    id="store_logo"
                                    accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2.5 file:px-5
                                        file:rounded-xl file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        dark:file:bg-indigo-900/30 dark:file:text-indigo-400
                                        hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50
                                        file:transition file:duration-200 file:cursor-pointer
                                        cursor-pointer"
                                    onchange="previewLogo(this)"
                                />
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Format: JPEG, PNG, WebP, GIF. Maksimal 5MB. Gambar akan dikompresi otomatis.</p>
                            </div>

                            {{-- New Logo Preview --}}
                            <div id="logo-preview-container" class="mt-4 hidden">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 font-medium uppercase tracking-wider">Preview Logo Baru</p>
                                <img id="logo-preview" src="#" alt="Preview" class="h-20 w-auto object-contain rounded-lg shadow-sm border border-indigo-200 dark:border-indigo-700 p-1" />
                            </div>

                            @error('store_logo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="flex items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Pengaturan
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition duration-200">
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewLogo(input) {
            const container = document.getElementById('logo-preview-container');
            const preview = document.getElementById('logo-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.classList.remove('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                container.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
