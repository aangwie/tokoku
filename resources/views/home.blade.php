<x-customer-layout>
    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-white via-white to-[#91ebff]/30 dark:from-gray-800 dark:to-gray-800/40 border-b border-gray-150 dark:border-gray-700 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative z-10 max-w-2xl">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-950 dark:text-white sm:text-5xl">
                    Selamat Datang di <span class="bg-gradient-to-r from-indigo-650 to-[#5ad6f2] bg-clip-text text-transparent dark:from-[#91ebff] dark:to-white">{{ \App\Models\Setting::get('store_name', config('app.name', 'BN Boutique')) }}</span>
                </h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">
                    Temukan koleksi produk berkualitas tinggi dengan penawaran terbaik. Nikmati kemudahan berbelanja dengan multi-payment gateway aman dan notifikasi otomatis.
                </p>
            </div>
        </div>
        {{-- Floating glow nodes --}}
        <div class="absolute right-0 top-0 -mr-16 -mt-16 w-80 h-80 rounded-full bg-[#91ebff]/30 blur-3xl opacity-70"></div>
        <div class="absolute right-32 bottom-0 w-32 h-32 rounded-full bg-indigo-500/5 blur-2xl"></div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row gap-8">
            
            <!-- Category Filter Sidebar -->
            <div class="w-full md:w-64 shrink-0">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white mb-4">Kategori</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request('category') == '' ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                                Semua Kategori
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li>
                                <a href="{{ route('home', ['category' => $category->slug]) }}" class="block px-3 py-2 rounded-md text-sm font-medium {{ request('category') == $category->slug ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400' : 'text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700/50' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="flex-1">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white mb-6">Produk Pilihan</h2>
                
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
</x-customer-layout>
