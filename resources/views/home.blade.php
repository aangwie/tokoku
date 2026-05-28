<x-customer-layout>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="productSearch()">
        <!-- Filter Bar -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 mb-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Category Select -->
                <div>
                    <label for="category-select" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Kategori
                    </label>
                    <select id="category-select" 
                            onchange="window.location.href = this.value"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200">
                        <option value="{{ route('home') }}" {{ request('category') == '' ? 'selected' : '' }}>
                            Semua Kategori
                        </option>
                        @foreach($categories as $category)
                            <option value="{{ route('home', ['category' => $category->slug]) }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Product Search with Autocomplete -->
                <div class="relative">
                    <label for="product-search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cari Produk
                    </label>
                    <div class="relative">
                        <input type="text" 
                               id="product-search"
                               x-model="searchQuery"
                               @input="searchProducts()"
                               @focus="showResults = true"
                               @click.away="showResults = false"
                               placeholder="Ketik nama produk..."
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition duration-200 pl-4 pr-10 py-2">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        
                        <!-- Autocomplete Results -->
                        <div x-show="showResults && searchResults.length > 0"
                             x-transition
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 max-h-96 overflow-y-auto">
                            <template x-for="product in searchResults" :key="product.id">
                                <a :href="product.url" 
                                   class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                    <img :src="product.image" 
                                         :alt="product.name" 
                                         class="w-12 h-12 object-cover rounded">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="product.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="product.category"></p>
                                        <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400" x-text="product.price"></p>
                                    </div>
                                </a>
                            </template>
                        </div>
                        
                        <!-- No Results Message -->
                        <div x-show="showResults && searchQuery.length > 0 && searchResults.length === 0"
                             x-transition
                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Tidak ada produk ditemukan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="flex-1">
                
                @if($products->isEmpty())
                    <div class="bg-white dark:bg-gray-800 text-center py-12 rounded-lg border border-gray-100 dark:border-gray-700">
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada produk yang ditemukan.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition duration-200 border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                                <a href="{{ route('products.show', $product->slug) }}" class="block overflow-hidden relative">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition duration-200">
                                    @else
                                        <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M21.9 2.19a1 1 0 0 0-1.4 0L1.7 20.9A1 1 0 0 0 3.1 22.3l18.8-18.7a1 1 0 0 0 0-1.4zM4.3 19.3L15.9 7.7l1.4 1.4-11.6 11.6zm15-4.4v3.1l-2.1-2.1z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                                <div class="p-6 flex-grow flex flex-col justify-between">
                                    <div>
                                        <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">
                                            {{ $product->category->name }}
                                        </span>
                                        <a href="{{ route('products.show', $product->slug) }}" class="block mt-1">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition duration-150">
                                                {{ $product->name }}
                                            </h3>
                                        </a>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 line-clamp-2">
                                            {{ Str::limit(strip_tags($product->description), 100) }}
                                        </p>
                                    </div>
                                    <div class="mt-4">
                                        <!-- Shipping Indicator -->
                                        <div class="mb-3">
                                            @if($product->is_free_shipping)
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-50 dark:bg-green-900/20 rounded-full">
                                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-green-700 dark:text-green-400">Gratis Ongkir</span>
                                                </div>
                                            @else
                                                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 dark:bg-red-900/20 rounded-full">
                                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                                                    </svg>
                                                    <span class="text-xs font-semibold text-red-700 dark:text-red-400">Berbayar</span>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex justify-between items-center mb-4">
                                            <span class="text-lg font-extrabold text-gray-950 dark:text-white">
                                                Rp {{ number_format($product->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                Stok: {{ $product->stock }}
                                            </span>
                                        </div>
                                        
                                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 shadow-sm transition duration-150">
                                                + Keranjang
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
        </div>
    </div>

    @php
        $productsData = $products->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'category' => $product->category->name,
                'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                'image' => $product->image ? asset($product->image) : '',
                'url' => route('products.show', $product->slug)
            ];
        });
    @endphp

    <script>
        function productSearch() {
            return {
                searchQuery: '',
                searchResults: [],
                showResults: false,
                allProducts: @json($productsData),

                searchProducts() {
                    if (this.searchQuery.length < 2) {
                        this.searchResults = [];
                        return;
                    }

                    const query = this.searchQuery.toLowerCase();
                    this.searchResults = this.allProducts.filter(product => 
                        product.name.toLowerCase().includes(query) ||
                        product.category.toLowerCase().includes(query)
                    ).slice(0, 5); // Limit to 5 results
                }
            }
        }
    </script>
</x-customer-layout>
