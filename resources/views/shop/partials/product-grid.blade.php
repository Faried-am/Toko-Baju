@if ($searchTerm)
    <p class="text-gray-600 text-sm mb-4">
        Menampilkan {{ $products->count() }} hasil untuk "<span class="font-semibold">{{ $searchTerm }}</span>"
    </p>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    @forelse ($products as $product)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">

            <a href="{{ route('shop.show', $product) }}">
                <div class="h-48 bg-gray-100 flex items-center justify-center">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-gray-400">Tidak ada gambar</span>
                    @endif
                </div>
            </a>

            <div class="p-4">
                <p class="text-xs text-green-600 uppercase font-semibold">
                    {{ $product->category->name ?? 'Tanpa Kategori' }}
                </p>
                <a href="{{ route('shop.show', $product) }}">
                    <h3 class="text-lg font-bold text-gray-800 mt-1 hover:text-green-600 transition">
                        {{ $product->name }}
                    </h3>
                </a>
                @if ($product->reviews_count > 0)
    <p class="text-sm text-yellow-500 mt-1">
        ★ {{ number_format($product->reviews_avg_rating, 1) }}
        <span class="text-gray-400">({{ $product->reviews_count }})</span>
    </p>
@endif
                <p class="text-xl font-bold text-green-600 mt-2">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>

<a href="{{ route('shop.show', $product) }}" class="block w-full mt-3 bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-medium text-center">
    Pilih Size
</a>            </div>

        </div>
    @empty
        <p class="text-gray-500 col-span-4 text-center">Tidak ada produk ditemukan.</p>
    @endforelse

</div>