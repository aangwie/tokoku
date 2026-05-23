<x-customer-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ showImageOverlay: false }">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
                
                <!-- Product Image -->
                <div>
                    @if($product->image)
                        <div class="relative group cursor-pointer" @click="showImageOverlay = true">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[500px] object-cover rounded-lg shadow-inner transition duration-300 group-hover:opacity-90">
                            <!-- Zoom Icon Overlay -->
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 bg-black/10 rounded-lg">
                                <div class="bg-white/90 dark:bg-gray-800/90 rounded-full p-3 shadow-lg">
                                    <svg class="w-8 h-8 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="w-full h-80 bg-gray-100 dark:bg-gray-700 flex items-center justify-center rounded-lg">
                            <svg class="w-24 h-24 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M21.9 2.19a1 1 0 0 0-1.4 0L1.7 20.9A1 1 0 0 0 3.1 22.3l18.8-18.7a1 1 0 0 0 0-1.4zM4.3 19.3L15.9 7.7l1.4 1.4-11.6 11.6zm15-4.4v3.1l-2.1-2.1z"></path>
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Product Details -->
                <div class="flex flex-col justify-between">
                    <div>
                        <div class="mb-4">
                            <span class="px-3 py-1 text-xs font-semibold uppercase tracking-wider text-indigo-700 bg-indigo-50 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-full">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h1>
                        
                        <div class="text-2xl font-black text-indigo-600 dark:text-indigo-400 mb-6">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        <!-- Product Specs -->
                        <div class="border-t border-b border-gray-100 dark:border-gray-700 py-4 mb-6 text-sm text-gray-600 dark:text-gray-400 grid grid-cols-2 gap-4">
                            <div>
                                <span class="block text-gray-400">Ketersediaan Stok</span>
                                <span class="font-semibold text-gray-900 dark:text-white">
                                    {{ $product->stock > 0 ? $product->stock . ' Unit' : 'Stok Habis' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-gray-400">Berat Produk</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ $product->weight }} gram</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-2">Deskripsi Produk</h3>
                            <div class="text-gray-600 dark:text-gray-400 leading-relaxed text-sm whitespace-pre-line">
                                {{ $product->description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Form -->
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="border-t border-gray-100 dark:border-gray-700 pt-6">
                            @csrf
                            <div class="flex items-end gap-4">
                                <div class="w-24">
                                    <label for="quantity" class="block text-xs font-medium text-gray-500 uppercase mb-1">Jumlah</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" required class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                </div>
                                <button type="submit" class="flex-grow inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition duration-150">
                                    Tambahkan ke Keranjang Belanja
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                            <button disabled class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-md text-white bg-gray-400 cursor-not-allowed">
                                Stok Habis
                            </button>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Image Overlay Modal -->
        @if($product->image)
            <div x-show="showImageOverlay" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm"
                 @click.self="showImageOverlay = false"
                 style="display: none;">
                
                <!-- Close Button -->
                <button @click="showImageOverlay = false" 
                        class="absolute top-4 right-4 p-2 rounded-full bg-white/90 dark:bg-gray-800/90 hover:bg-white dark:hover:bg-gray-800 transition duration-200 shadow-lg z-10">
                    <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Image Container (50% of screen) -->
                <div class="relative w-full h-full max-w-[50vw] max-h-[50vh] flex items-center justify-center"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95">
                    <img src="{{ asset($product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
                         @click.stop>
                </div>
            </div>
        @endif
    </div>
</x-customer-layout>
