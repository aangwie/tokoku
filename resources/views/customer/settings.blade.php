<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Pengaturan Akun') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8" x-data="customerSettings()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- ==================== PROFIL PEMBELI ==================== --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-8">
                    {{-- Section Header --}}
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Profil Saya</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Perbarui nama, nomor handphone, dan kata sandi Anda</p>
                            </div>
                        </div>
                    </div>

                    {{-- Form Edit Profil --}}
                    <form action="{{ route('customer.profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @if ($errors->any())
                            <div class="bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-800 rounded-xl p-4 text-sm text-red-600 dark:text-red-400">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                       placeholder="Nama lengkap Anda"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2.5" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nomor Handphone</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                                       placeholder="Contoh: 081234567890"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2.5" />
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 mb-3 mt-4">Ubah Password <span class="text-xs font-normal text-gray-400 dark:text-gray-500">(Kosongkan jika tidak ingin diubah)</span></h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Password Baru</label>
                                    <input type="password" name="password"
                                           placeholder="Minimal 8 karakter"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2.5" />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Konfirmasi Password Baru</label>
                                    <input type="password" name="password_confirmation"
                                           placeholder="Ketik ulang password baru"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2.5" />
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-gray-150 dark:border-gray-750">
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-102">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Perbarui Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ==================== ALAMAT PENGIRIMAN ==================== --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-8">
                    {{-- Section Header --}}
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Alamat Pengiriman</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kelola alamat pengiriman Anda (bisa lebih dari satu)</p>
                            </div>
                        </div>
                        <button type="button" @click="showAddressForm = !showAddressForm; editingAddress = null; resetAddressForm()"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-600 hover:to-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Alamat
                        </button>
                    </div>

                    {{-- Add/Edit Address Form --}}
                    <div x-show="showAddressForm"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         x-cloak
                         class="mb-6">
                        <form :action="editingAddress ? '{{ url('/settings/address') }}/' + editingAddress : '{{ route('customer.address.store') }}'" method="POST"
                              class="bg-gradient-to-br from-cyan-50 to-blue-50 dark:from-cyan-950/20 dark:to-blue-950/20 rounded-2xl border border-cyan-200 dark:border-cyan-800 p-6">
                            @csrf
                            <template x-if="editingAddress">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                            <h4 class="font-bold text-gray-900 dark:text-white mb-4" x-text="editingAddress ? 'Edit Alamat' : 'Tambah Alamat Baru'"></h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Label Alamat</label>
                                    <select name="label" x-model="addressForm.label"
                                            data-hs-select='{
                                                "hasSearch": true,
                                                "searchPlaceholder": "Cari label...",
                                                "placeholder": "Pilih Label"
                                            }'
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-3 py-2.5">
                                        <option value="Rumah">🏠 Rumah</option>
                                        <option value="Kantor">🏢 Kantor</option>
                                        <option value="Apartemen">🏬 Apartemen</option>
                                        <option value="Kos">🏘️ Kos</option>
                                        <option value="Lainnya">📍 Lainnya</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nama Penerima</label>
                                    <input type="text" name="recipient_name" x-model="addressForm.recipient_name" required
                                           placeholder="Nama lengkap penerima"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-3 py-2.5" />
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nomor Telepon</label>
                                <input type="text" name="phone" x-model="addressForm.phone" required
                                       placeholder="08xx xxxx xxxx"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-3 py-2.5" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Provinsi</label>
                                    <select name="province_code" x-model="addressForm.province_code" @change="onProvinceChange" required
                                            data-hs-select='{
                                                "hasSearch": true,
                                                "searchPlaceholder": "Cari provinsi...",
                                                "placeholder": "Pilih Provinsi"
                                            }'
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-3 py-2.5">
                                        <option value="">Pilih Provinsi</option>
                                        <template x-for="province in provinces" :key="province.id">
                                            <option :value="province.id" x-text="province.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Kota/Kabupaten</label>
                                    <select name="city_code" x-model="addressForm.city_code" @change="onCityChange" required
                                            :disabled="!addressForm.province_code"
                                            data-hs-select='{
                                                "hasSearch": true,
                                                "searchPlaceholder": "Cari kota/kabupaten...",
                                                "placeholder": "Pilih Kota/Kabupaten"
                                            }'
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-3 py-2.5 disabled:opacity-50">
                                        <option value="">Pilih Kota/Kabupaten</option>
                                        <template x-for="city in cities" :key="city.id">
                                            <option :value="city.id" x-text="city.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Kecamatan</label>
                                    <select name="district_code" x-model="addressForm.district_code" @change="onDistrictChange" required
                                            :disabled="!addressForm.city_code"
                                            data-hs-select='{
                                                "hasSearch": true,
                                                "searchPlaceholder": "Cari kecamatan...",
                                                "placeholder": "Pilih Kecamatan"
                                            }'
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-3 py-2.5 disabled:opacity-50">
                                        <option value="">Pilih Kecamatan</option>
                                        <template x-for="district in districts" :key="district.id">
                                            <option :value="district.id" x-text="district.name"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Kelurahan/Desa</label>
                                    <select name="village_code" x-model="addressForm.village_code" @change="updateFullAddress" required
                                            :disabled="!addressForm.district_code"
                                            data-hs-select='{
                                                "hasSearch": true,
                                                "searchPlaceholder": "Cari kelurahan/desa...",
                                                "placeholder": "Pilih Kelurahan/Desa"
                                            }'
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-3 py-2.5 disabled:opacity-50">
                                        <option value="">Pilih Kelurahan/Desa</option>
                                        <template x-for="village in villages" :key="village.id">
                                            <option :value="village.id" x-text="village.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Alamat Jalan / Patokan</label>
                                <textarea name="street_address" x-model="addressForm.street_address" @input="updateFullAddress" rows="2" required
                                          placeholder="Contoh: Jl. Merdeka No. 123, RT 01/RW 02"
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-cyan-500 focus:ring-cyan-500 px-3 py-2.5 resize-none"></textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Masukkan nama jalan, nomor rumah, RT/RW, atau patokan lainnya</p>
                            </div>

                            <input type="hidden" name="full_address" x-model="addressForm.full_address">

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_default" value="1" x-model="addressForm.is_default"
                                           class="rounded border-gray-300 text-cyan-600 focus:ring-cyan-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Jadikan alamat utama</span>
                                </label>
                                <div class="flex gap-3">
                                    <button type="button" @click="showAddressForm = false; editingAddress = null"
                                            class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium transition">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="inline-flex items-center px-5 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span x-text="editingAddress ? 'Perbarui' : 'Simpan'"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Address List --}}
                    @if($addresses->count() > 0)
                        <div class="space-y-3">
                            @foreach($addresses as $addr)
                                <div class="group relative bg-white dark:bg-gray-800 rounded-xl border-2 {{ $addr->is_default ? 'border-cyan-400 dark:border-cyan-600' : 'border-gray-100 dark:border-gray-700' }} p-5 transition-all duration-200 hover:shadow-md">
                                    {{-- Default Badge --}}
                                    @if($addr->is_default)
                                        <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-0.5 bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 text-xs font-bold rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Utama
                                        </span>
                                    @endif

                                    <div class="flex items-start gap-4">
                                        <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg shrink-0">
                                            <span class="text-lg">
                                                @switch($addr->label)
                                                    @case('Rumah') 🏠 @break
                                                    @case('Kantor') 🏢 @break
                                                    @case('Apartemen') 🏬 @break
                                                    @case('Kos') 🏘️ @break
                                                    @default 📍
                                                @endswitch
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $addr->label }}</span>
                                                <span class="text-gray-300 dark:text-gray-600">·</span>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $addr->recipient_name }}</span>
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $addr->phone }}</p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $addr->full_address }}</p>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex items-center gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                                        <button type="button"
                                                @click="editAddress({{ json_encode($addr) }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-lg transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('customer.address.destroy', $addr) }}" method="POST"
                                              onsubmit="return confirm('Hapus alamat ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50/50 dark:bg-gray-900/20 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                            <svg class="w-14 h-14 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Belum ada alamat pengiriman</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tambahkan alamat agar lebih mudah saat checkout</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ==================== REKENING BANK PENGEMBALIAN DANA ==================== --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                <div class="p-8">
                    {{-- Section Header --}}
                    <div class="flex items-center justify-between mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Rekening Bank</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Rekening untuk pengembalian dana (refund)</p>
                            </div>
                        </div>
                        <button type="button" @click="showBankForm = !showBankForm; editingBank = null; resetBankForm()"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Rekening
                        </button>
                    </div>

                    {{-- Add/Edit Bank Form --}}
                    <div x-show="showBankForm"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0 -translate-y-2"
                         x-cloak
                         class="mb-6">
                        <form :action="editingBank ? '{{ url('/settings/bank-account') }}/' + editingBank : '{{ route('customer.bank.store') }}'" method="POST"
                              class="bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-950/20 dark:to-teal-950/20 rounded-2xl border border-emerald-200 dark:border-emerald-800 p-6">
                            @csrf
                            <template x-if="editingBank">
                                <input type="hidden" name="_method" value="PUT">
                            </template>

                            <h4 class="font-bold text-gray-900 dark:text-white mb-4" x-text="editingBank ? 'Edit Rekening Bank' : 'Tambah Rekening Bank Baru'"></h4>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nama Bank</label>
                                    <input type="text" name="bank_name" x-model="bankForm.bank_name" required
                                           placeholder="Contoh: BCA, BNI, Mandiri"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2.5" />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Nomor Rekening</label>
                                    <input type="text" name="account_number" x-model="bankForm.account_number" required
                                           placeholder="Contoh: 1234567890"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2.5" />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1.5 uppercase tracking-wider">Atas Nama</label>
                                    <input type="text" name="account_holder" x-model="bankForm.account_holder" required
                                           placeholder="Nama pemilik rekening"
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 px-3 py-2.5" />
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_default" value="1" x-model="bankForm.is_default"
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Jadikan rekening utama</span>
                                </label>
                                <div class="flex gap-3">
                                    <button type="button" @click="showBankForm = false; editingBank = null"
                                            class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-medium transition">
                                        Batal
                                    </button>
                                    <button type="submit"
                                            class="inline-flex items-center px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span x-text="editingBank ? 'Perbarui' : 'Simpan'"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Bank Account List --}}
                    @if($bankAccounts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($bankAccounts as $bank)
                                <div class="group relative bg-white dark:bg-gray-800 rounded-xl border-2 {{ $bank->is_default ? 'border-emerald-400 dark:border-emerald-600' : 'border-gray-100 dark:border-gray-700' }} p-5 transition-all duration-200 hover:shadow-md">
                                    {{-- Default Badge --}}
                                    @if($bank->is_default)
                                        <span class="absolute top-3 right-3 inline-flex items-center gap-1 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 text-xs font-bold rounded-full">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                            Utama
                                        </span>
                                    @endif

                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                        </div>
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $bank->bank_name }}</span>
                                    </div>

                                    <div class="space-y-1.5 text-sm mb-4">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">No. Rekening</span>
                                            <span class="font-semibold text-gray-900 dark:text-white font-mono tracking-wider">{{ $bank->account_number }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 dark:text-gray-400">Atas Nama</span>
                                            <span class="font-medium text-gray-900 dark:text-white">{{ $bank->account_holder }}</span>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="flex items-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                                        <button type="button"
                                                @click="editBank({{ json_encode($bank) }})"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/40 rounded-lg transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('customer.bank.destroy', $bank) }}" method="POST"
                                              onsubmit="return confirm('Hapus rekening ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/30 hover:bg-red-100 dark:hover:bg-red-900/40 rounded-lg transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50/50 dark:bg-gray-900/20 rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-700">
                            <svg class="w-14 h-14 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">Belum ada rekening bank</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Tambahkan rekening bank untuk mempermudah proses pengembalian dana</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- FlyonUI CDN for Select with Search --}}
    <link href="https://cdn.jsdelivr.net/npm/flyonui@2.4.1/dist/css/flyonui.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flyonui@2.4.1/dist/js/flyonui.min.js"></script>

    <script>
        function customerSettings() {
            return {
                // Address
                showAddressForm: false,
                editingAddress: null,
                addressForm: { 
                    label: 'Rumah', 
                    recipient_name: '', 
                    phone: '', 
                    province_code: '',
                    city_code: '',
                    district_code: '',
                    village_code: '',
                    street_address: '',
                    full_address: '', 
                    is_default: false 
                },

                // Wilayah data
                provinces: [],
                cities: [],
                districts: [],
                villages: [],

                // Bank
                showBankForm: false,
                editingBank: null,
                bankForm: { bank_name: '', account_number: '', account_holder: '', is_default: false },

                async init() {
                    await this.loadProvinces();
                },

                async loadProvinces() {
                    try {
                        const response = await fetch('/api/provinces');
                        this.provinces = await response.json();
                    } catch (error) {
                        console.error('Failed to load provinces:', error);
                    }
                },

                async onProvinceChange() {
                    this.addressForm.city_code = '';
                    this.addressForm.district_code = '';
                    this.addressForm.village_code = '';
                    this.cities = [];
                    this.districts = [];
                    this.villages = [];

                    if (this.addressForm.province_code) {
                        try {
                            const response = await fetch(`/api/cities/${this.addressForm.province_code}`);
                            this.cities = await response.json();
                        } catch (error) {
                            console.error('Failed to load cities:', error);
                        }
                    }
                    this.updateFullAddress();
                },

                async onCityChange() {
                    this.addressForm.district_code = '';
                    this.addressForm.village_code = '';
                    this.districts = [];
                    this.villages = [];

                    if (this.addressForm.city_code) {
                        try {
                            const response = await fetch(`/api/districts/${this.addressForm.city_code}`);
                            this.districts = await response.json();
                        } catch (error) {
                            console.error('Failed to load districts:', error);
                        }
                    }
                    this.updateFullAddress();
                },

                async onDistrictChange() {
                    this.addressForm.village_code = '';
                    this.villages = [];

                    if (this.addressForm.district_code) {
                        try {
                            const response = await fetch(`/api/villages/${this.addressForm.district_code}`);
                            this.villages = await response.json();
                        } catch (error) {
                            console.error('Failed to load villages:', error);
                        }
                    }
                    this.updateFullAddress();
                },

                updateFullAddress() {
                    const parts = [];
                    
                    if (this.addressForm.street_address) {
                        parts.push(this.addressForm.street_address);
                    }
                    
                    const village = this.villages.find(v => v.id == this.addressForm.village_code);
                    if (village) parts.push(village.name);
                    
                    const district = this.districts.find(d => d.id == this.addressForm.district_code);
                    if (district) parts.push(district.name);
                    
                    const city = this.cities.find(c => c.id == this.addressForm.city_code);
                    if (city) parts.push(city.name);
                    
                    const province = this.provinces.find(p => p.id == this.addressForm.province_code);
                    if (province) parts.push(province.name);
                    
                    this.addressForm.full_address = parts.join(', ');
                },

                resetAddressForm() {
                    this.addressForm = { 
                        label: 'Rumah', 
                        recipient_name: '', 
                        phone: '', 
                        province_code: '',
                        city_code: '',
                        district_code: '',
                        village_code: '',
                        street_address: '',
                        full_address: '', 
                        is_default: false 
                    };
                    this.cities = [];
                    this.districts = [];
                    this.villages = [];
                },

                async editAddress(addr) {
                    this.editingAddress = addr.id;
                    this.addressForm = {
                        label: addr.label,
                        recipient_name: addr.recipient_name,
                        phone: addr.phone,
                        province_code: addr.province_code || '',
                        city_code: addr.city_code || '',
                        district_code: addr.district_code || '',
                        village_code: addr.village_code || '',
                        street_address: addr.street_address || '',
                        full_address: addr.full_address,
                        is_default: addr.is_default
                    };

                    // Load cascading data for edit
                    if (addr.province_code) {
                        const response = await fetch(`/api/cities/${addr.province_code}`);
                        this.cities = await response.json();
                    }
                    if (addr.city_code) {
                        const response = await fetch(`/api/districts/${addr.city_code}`);
                        this.districts = await response.json();
                    }
                    if (addr.district_code) {
                        const response = await fetch(`/api/villages/${addr.district_code}`);
                        this.villages = await response.json();
                    }

                    this.showAddressForm = true;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },

                resetBankForm() {
                    this.bankForm = { bank_name: '', account_number: '', account_holder: '', is_default: false };
                },

                editBank(bank) {
                    this.editingBank = bank.id;
                    this.bankForm = {
                        bank_name: bank.bank_name,
                        account_number: bank.account_number,
                        account_holder: bank.account_holder,
                        is_default: bank.is_default
                    };
                    this.showBankForm = true;
                }
            };
        }

    </script>
</x-app-layout>
