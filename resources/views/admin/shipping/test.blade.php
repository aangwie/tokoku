<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Test API Kurir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Configuration Status --}}
            <div class="mb-6 p-4 rounded-lg {{ $isConfigured ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800' }}">
                <div class="flex items-center gap-2">
                    @if($isConfigured)
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-green-800 dark:text-green-200 font-medium">API Shipping Terkonfigurasi</span>
                    @else
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-red-800 dark:text-red-200 font-medium">API Shipping Belum Dikonfigurasi</span>
                    @endif
                </div>
            </div>

            {{-- Store Information --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informasi Toko (Origin)</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Provinsi:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $storeProvince->name ?? 'Belum diset' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Kota/Kabupaten:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-gray-100">{{ $storeCity->name ?? 'Belum diset' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">API Key:</span>
                            <span class="ml-2 font-mono text-xs text-gray-900 dark:text-gray-100">{{ $shippingApiKey ? substr($shippingApiKey, 0, 20) . '...' : 'Belum diset' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Kurir:</span>
                            <span class="ml-2 font-medium text-gray-900 dark:text-gray-100 uppercase">{{ $preferredCourier }}</span>
                        </div>
                    </div>
                    @if(!$isConfigured)
                        <div class="mt-4">
                            <a href="{{ route('admin.settings.edit') }}" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">
                                → Konfigurasi di Pengaturan Toko
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Test Form --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Test Perhitungan Ongkir</h3>
                    
                    <form action="{{ route('admin.shipping.test') }}" method="POST" x-data="shippingTest()">
                        @csrf
                        
                        <div class="space-y-4">
                            {{-- Destination Province --}}
                            <div>
                                <label for="destination_province" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Provinsi Tujuan
                                </label>
                                <select id="destination_province" 
                                        x-model="selectedProvince"
                                        @change="filterCities()"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">-- Pilih Provinsi --</option>
                                    @foreach($provinces as $province)
                                        <option value="{{ $province->code }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Destination City --}}
                            <div>
                                <label for="destination_city_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Kota/Kabupaten Tujuan
                                </label>
                                <select name="destination_city_code" 
                                        id="destination_city_code"
                                        x-model="selectedCity"
                                        :disabled="!selectedProvince"
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:opacity-50">
                                    <option value="">-- Pilih Kota/Kabupaten --</option>
                                    <template x-for="city in filteredCities" :key="city.code">
                                        <option :value="city.code" x-text="city.name"></option>
                                    </template>
                                </select>
                                @error('destination_city_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Weight --}}
                            <div>
                                <label for="weight" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Berat (gram)
                                </label>
                                <input type="number" 
                                       name="weight" 
                                       id="weight" 
                                       value="{{ old('weight', $weight ?? 1000) }}"
                                       min="1"
                                       step="100"
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Contoh: 1000">
                                @error('weight')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-xs text-gray-500">1000 gram = 1 kg</p>
                            </div>

                            {{-- Submit Button --}}
                            <div class="flex gap-3">
                                <button type="submit" 
                                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow-sm transition">
                                    Test API
                                </button>
                                <a href="{{ route('admin.dashboard') }}" 
                                   class="px-6 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition">
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Test Result --}}
            @if(isset($result))
                <div class="mt-6 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Hasil Test</h3>
                        
                        @if($result['success'])
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-semibold text-green-800 dark:text-green-200 mb-3">✅ API Berhasil!</p>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Kurir:</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $result['data']['courier'] ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Layanan:</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $result['data']['service'] ?? 'Regular' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Tujuan:</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $result['data']['destination'] }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Berat:</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $result['data']['weight'] }} gram</span>
                                            </div>
                                            <div class="flex justify-between border-t pt-2 mt-2">
                                                <span class="text-gray-600 dark:text-gray-400">Ongkos Kirim:</span>
                                                <span class="font-bold text-lg text-green-600 dark:text-green-400">{{ $result['data']['formatted_cost'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="font-semibold text-red-800 dark:text-red-200 mb-2">❌ Test Gagal</p>
                                        <p class="text-sm text-red-700 dark:text-red-300 mb-4">{{ $result['error'] }}</p>
                                        
                                        @if(isset($result['debug']) && !empty($result['debug']))
                                            <details class="mt-4">
                                                <summary class="cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                                    🔍 Informasi Debug (klik untuk lihat detail)
                                                </summary>
                                                <div class="mt-3 p-3 bg-gray-100 dark:bg-gray-900 rounded text-xs font-mono overflow-auto max-h-96">
                                                    <pre class="text-gray-800 dark:text-gray-200">{{ json_encode($result['debug'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            </details>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function shippingTest() {
            return {
                selectedProvince: '',
                selectedCity: '',
                allCities: @json($cities),
                filteredCities: [],

                filterCities() {
                    if (!this.selectedProvince) {
                        this.filteredCities = [];
                        this.selectedCity = '';
                        return;
                    }

                    this.filteredCities = this.allCities.filter(city => {
                        const cityProvinceCode = city.code.substring(0, 2);
                        return cityProvinceCode === this.selectedProvince;
                    });

                    this.selectedCity = '';
                }
            };
        }
    </script>
</x-app-layout>
