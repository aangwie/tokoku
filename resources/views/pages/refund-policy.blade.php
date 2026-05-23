<x-customer-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Back Link --}}
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Beranda
        </a>

        {{-- Page Content --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
            <div class="prose prose-indigo dark:prose-invert max-w-none">
                {!! $content !!}
            </div>
        </div>

        {{-- Last Updated --}}
        <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            Terakhir diperbarui: {{ now()->format('d F Y') }}
        </div>
    </div>
</x-customer-layout>