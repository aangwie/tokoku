<x-customer-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
                
                <!-- Product Image -->
                <div>
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[500px] object-cover rounded-lg shadow-inner">
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
    </div>
</x-customer-layout>
