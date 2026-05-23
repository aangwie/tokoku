<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pengaturan Toko') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ==================== SECTION 1: Konfigurasi Umum ==================== --}}
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
                            <p class="text-sm text-gray-500 dark:text-gray-400">Atur nama, WhatsApp konfirmasi, dan logo toko Anda</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data"
                          x-data="settingsForm()" id="settings-form">
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

                        {{-- Store WhatsApp Confirmation --}}
                        <div class="mb-8">
                            <label for="store_whatsapp" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Nomor WhatsApp Konfirmasi Pembayaran
                            </label>
                            <input
                                type="text"
                                name="store_whatsapp"
                                id="store_whatsapp"
                                value="{{ old('store_whatsapp', $settings['store_whatsapp']) }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 px-4 py-3"
                                placeholder="Contoh: 6281234567890"
                                required
                            />
                            <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Nomor WhatsApp tujuan yang digunakan untuk konfirmasi pembayaran manual oleh pembeli. Gunakan format angka saja dengan kode negara di awal (contoh: 6281234567890).</p>
                            @error('store_whatsapp')
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
                                        src="{{ str_starts_with($settings['store_logo'], 'data:') ? $settings['store_logo'] : asset($settings['store_logo']) }}"
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
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Format: JPEG, PNG, WebP, GIF. Maksimal 1MB. Gambar akan dikonversi ke WebP dan disimpan sebagai base64.</p>
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

                        {{-- ==================== Payment Method Selection ==================== --}}
                        <div class="mb-8">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">
                                Metode Pembayaran Toko
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- Payment Gateway Option --}}
                                <label class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-indigo-400 dark:hover:border-indigo-500 bg-white dark:bg-gray-800"
                                       :class="paymentMethod === 'paymentgateway' ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/20' : 'border-gray-200 dark:border-gray-700'">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">💳</span>
                                            <span class="font-bold text-gray-900 dark:text-white">Payment Gateway</span>
                                        </div>
                                        <input type="radio" name="payment_method" value="paymentgateway"
                                               class="text-indigo-600 focus:ring-indigo-500"
                                               x-model="paymentMethod"
                                               {{ $settings['payment_method'] === 'paymentgateway' ? 'checked' : '' }}>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Pembayaran otomatis menggunakan Midtrans. Pelanggan dapat membayar via Virtual Account, QRIS, E-Wallet, dll.</p>
                                </label>

                                {{-- Manual Bank Transfer Option --}}
                                <label class="relative flex flex-col p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-indigo-400 dark:hover:border-indigo-500 bg-white dark:bg-gray-800"
                                       :class="paymentMethod === 'transfer' ? 'border-indigo-500 bg-indigo-50/50 dark:bg-indigo-950/20' : 'border-gray-200 dark:border-gray-700'">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="text-2xl">🏦</span>
                                            <span class="font-bold text-gray-900 dark:text-white">Transfer Bank Manual</span>
                                        </div>
                                        <input type="radio" name="payment_method" value="transfer"
                                               class="text-indigo-600 focus:ring-indigo-500"
                                               x-model="paymentMethod"
                                               {{ $settings['payment_method'] === 'transfer' ? 'checked' : '' }}>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">Pembayaran manual via transfer bank. Toko akan menampilkan rekening bank pada halaman detail pesanan pelanggan.</p>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ==================== BANK TRANSFER CONFIG PANEL ==================== --}}
                        <div x-show="paymentMethod === 'transfer'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             x-cloak
                             class="mb-8">
                            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-950/30 dark:to-indigo-950/30 rounded-2xl border border-blue-200 dark:border-blue-800 p-6">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2.5 bg-blue-600 rounded-lg shadow-sm">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 dark:text-white">Pengaturan Rekening Bank</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Tambahkan rekening bank tujuan transfer pembayaran</p>
                                        </div>
                                    </div>
                                    <button type="button" @click="addBankAccount()"
                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-all duration-200 transform hover:scale-105">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Tambah Rekening
                                    </button>
                                </div>

                                {{-- Bank Account List --}}
                                <div class="space-y-4">
                                    <template x-for="(account, index) in bankAccounts" :key="index">
                                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-blue-100 dark:border-blue-900 p-5 relative group transition-all duration-200 hover:shadow-md">
                                            {{-- Delete Button --}}
                                            <button type="button" @click="removeBankAccount(index)"
                                                    class="absolute top-3 right-3 p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-all duration-200 opacity-60 group-hover:opacity-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>

                                            <div class="flex items-center gap-2 mb-4">
                                                <span class="inline-flex items-center justify-center w-7 h-7 bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-bold rounded-full" x-text="index + 1"></span>
                                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Rekening Bank</span>
                                            </div>

                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Nama Bank</label>
                                                    <input type="text"
                                                           :name="'bank_accounts[' + index + '][bank_name]'"
                                                           x-model="account.bank_name"
                                                           placeholder="Contoh: BCA, BNI, Mandiri"
                                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2.5 transition duration-200" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Nomor Rekening</label>
                                                    <input type="text"
                                                           :name="'bank_accounts[' + index + '][account_number]'"
                                                           x-model="account.account_number"
                                                           placeholder="Contoh: 1234567890"
                                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2.5 transition duration-200" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Atas Nama</label>
                                                    <input type="text"
                                                           :name="'bank_accounts[' + index + '][account_holder]'"
                                                           x-model="account.account_holder"
                                                           placeholder="Nama pemilik rekening"
                                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500 px-3 py-2.5 transition duration-200" />
                                                </div>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Empty State --}}
                                    <div x-show="bankAccounts.length === 0" class="text-center py-8 bg-white/50 dark:bg-gray-800/50 rounded-xl border-2 border-dashed border-blue-200 dark:border-blue-800">
                                        <svg class="w-12 h-12 text-blue-300 dark:text-blue-700 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Belum ada rekening bank</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Klik tombol "Tambah Rekening" untuk menambahkan</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ==================== PAYMENT GATEWAY CONFIG PANEL ==================== --}}
                        <div x-show="paymentMethod === 'paymentgateway'"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             x-cloak
                             class="mb-8 space-y-6">

                            {{-- Midtrans Settings --}}
                            <div class="bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-950/30 dark:to-blue-950/30 rounded-2xl border border-cyan-200 dark:border-cyan-800 p-6">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="p-2.5 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-lg shadow-sm">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900 dark:text-white">Pengaturan Midtrans</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Konfigurasi API key untuk integrasi pembayaran Midtrans</p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    {{-- Client Key --}}
                                    <div>
                                        <label for="midtrans_client_key" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                                            Client Key
                                        </label>
                                        <div class="relative">
                                            <input type="text"
                                                   name="midtrans_client_key"
                                                   id="midtrans_client_key"
                                                   value="{{ old('midtrans_client_key', $settings['midtrans_client_key']) }}"
                                                   placeholder="SB-Mid-client-xxxxxxxxxxxx"
                                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-4 py-3 font-mono transition duration-200" />
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Digunakan di frontend untuk menampilkan popup pembayaran Snap.js</p>
                                        @error('midtrans_client_key')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Server Key --}}
                                    <div>
                                        <label for="midtrans_server_key" class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                                            Server Key
                                        </label>
                                        <div class="relative" x-data="{ showKey: false }">
                                            <input :type="showKey ? 'text' : 'password'"
                                                   name="midtrans_server_key"
                                                   id="midtrans_server_key"
                                                   value="{{ old('midtrans_server_key', $settings['midtrans_server_key']) }}"
                                                   placeholder="SB-Mid-server-xxxxxxxxxxxx"
                                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-4 py-3 pr-20 font-mono transition duration-200" />
                                            <button type="button" @click="showKey = !showKey"
                                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-cyan-500 transition">
                                                <svg x-show="!showKey" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <svg x-show="showKey" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Digunakan di backend untuk membuat transaksi dan verifikasi webhook. <span class="text-amber-500 font-medium">Rahasiakan!</span></p>
                                        @error('midtrans_server_key')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Environment --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                                            Environment
                                        </label>
                                        <div class="flex gap-4">
                                            <label class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition-all duration-200"
                                                   :class="midtransProduction === '0' ? 'border-cyan-500 bg-cyan-50 dark:bg-cyan-950/30' : 'border-gray-200 dark:border-gray-600 hover:border-cyan-300'">
                                                <input type="radio" name="midtrans_is_production" value="0"
                                                       x-model="midtransProduction"
                                                       class="text-cyan-600 focus:ring-cyan-500">
                                                <div>
                                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">🧪 Sandbox</span>
                                                    <p class="text-xs text-gray-400">Untuk testing</p>
                                                </div>
                                            </label>
                                            <label class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition-all duration-200"
                                                   :class="midtransProduction === '1' ? 'border-green-500 bg-green-50 dark:bg-green-950/30' : 'border-gray-200 dark:border-gray-600 hover:border-green-300'">
                                                <input type="radio" name="midtrans_is_production" value="1"
                                                       x-model="midtransProduction"
                                                       class="text-green-600 focus:ring-green-500">
                                                <div>
                                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">🚀 Production</span>
                                                    <p class="text-xs text-gray-400">Transaksi nyata</p>
                                                </div>
                                            </label>
                                        </div>
                                        @error('midtrans_is_production')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Info Box --}}
                            <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-5 h-5 text-amber-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-xs text-amber-800 dark:text-amber-300 leading-relaxed">
                                        <p class="font-semibold mb-1">Informasi Penting</p>
                                        <ul class="list-disc list-inside space-y-0.5 text-amber-700 dark:text-amber-400">
                                            <li>Dapatkan API key di <strong>dashboard.midtrans.com</strong></li>
                                            <li>Gunakan mode <strong>Sandbox</strong> untuk testing sebelum beralih ke Production</li>
                                            <li>Pastikan webhook/notification URL sudah dikonfigurasi di dashboard Midtrans</li>
                                            <li><strong>Jangan bagikan Server Key</strong> ke pihak yang tidak berwenang</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ==================== SECTION: Halaman Statis ==================== --}}
                        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-3 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Halaman Statis</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Kelola konten Syarat & Ketentuan dan Kebijakan Pengembalian Dana</p>
                                </div>
                            </div>

                            {{-- Terms and Conditions --}}
                            <div class="mb-6">
                                <label for="terms_and_conditions" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Syarat & Ketentuan
                                </label>
                                <textarea
                                    name="terms_and_conditions"
                                    id="terms_and_conditions"
                                    rows="10"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 px-4 py-3 font-mono text-sm"
                                    placeholder="Masukkan konten Syarat & Ketentuan dalam format HTML..."
                                >{{ old('terms_and_conditions', $settings['terms_and_conditions'] ?? '') }}</textarea>
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Gunakan HTML untuk formatting (contoh: <h2>, <p>, <ul>, <li>, dll)</p>
                                @error('terms_and_conditions')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Refund Policy --}}
                            <div class="mb-6">
                                <label for="refund_policy" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Kebijakan Pengembalian Dana
                                </label>
                                <textarea
                                    name="refund_policy"
                                    id="refund_policy"
                                    rows="10"
                                    class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 px-4 py-3 font-mono text-sm"
                                    placeholder="Masukkan konten Kebijakan Pengembalian Dana dalam format HTML..."
                                >{{ old('refund_policy', $settings['refund_policy'] ?? '') }}</textarea>
                                <p class="mt-2 text-xs text-gray-400 dark:text-gray-500">Gunakan HTML untuk formatting (contoh: <h2>, <p>, <ul>, <li>, dll)</p>
                                @error('refund_policy')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Info Box --}}
                            <div class="bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                                <div class="flex items-start gap-2.5">
                                    <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="text-xs text-blue-800 dark:text-blue-300 leading-relaxed">
                                        <p class="font-semibold mb-1">Informasi</p>
                                        <ul class="list-disc list-inside space-y-0.5 text-blue-700 dark:text-blue-400">
                                            <li>Halaman ini akan ditampilkan di footer website</li>
                                            <li>Gunakan HTML untuk formatting yang lebih baik</li>
                                            <li>Pastikan konten sesuai dengan peraturan yang berlaku</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
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

        function settingsForm() {
            return {
                paymentMethod: '{{ old('payment_method', $settings['payment_method']) }}',
                midtransProduction: '{{ old('midtrans_is_production', $settings['midtrans_is_production']) }}',
                bankAccounts: @json(old('bank_accounts', $settings['bank_accounts'])),

                addBankAccount() {
                    this.bankAccounts.push({
                        bank_name: '',
                        account_number: '',
                        account_holder: ''
                    });
                },

                removeBankAccount(index) {
                    this.bankAccounts.splice(index, 1);
                }
            };
        }
    </script>
</x-app-layout>
