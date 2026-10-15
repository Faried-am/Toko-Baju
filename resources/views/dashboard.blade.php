<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-6 mb-8">

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Total Produk</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalProducts }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Total Kategori</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalCategories }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Total Stok</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($totalStock, 0, ',', '.') }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Varian Stok Menipis</p>
                    <p class="text-3xl font-bold text-red-500 mt-2">{{ $lowStockVariants->count() }}</p>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>

            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Varian dengan Stok Menipis (≤ 5)</h3>

                @forelse ($lowStockVariants as $variant)
                    <div class="flex justify-between items-center border-b border-gray-100 py-2">
                        <span class="text-gray-700">
                            {{ $variant->product->name ?? 'Produk telah dihapus' }}
                            <span class="text-gray-400 text-sm">
                                · {{ $variant->color ?: 'Standar' }} · Size {{ $variant->size }}
                            </span>
                        </span>
                        @if ($variant->stock <= 0)
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-medium">Habis</span>
                        @else
                            <span class="text-red-500 font-semibold">Sisa {{ $variant->stock }}</span>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-500">Semua varian masih aman stoknya.</p>
                @endforelse
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Penjualan Terbaru</h3>
                    <a href="{{ route('sales.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                        Lihat Semua →
                    </a>
                </div>

                @forelse ($recentSales as $sale)
                    <div class="flex justify-between items-center border-b border-gray-100 py-2">
                        <div>
                            <span class="text-gray-700">{{ $sale->product->name ?? 'Produk telah dihapus' }}</span>
                            <span class="text-gray-400 text-sm"> × {{ $sale->quantity }}</span>
                        </div>
                        <span class="text-green-600 font-semibold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada penjualan.</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>