<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Produk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <a href="{{ route('shop.index') }}" class="text-green-600 hover:text-green-700 font-medium mb-4 inline-block">
                ← Kembali ke Toko
            </a>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">

                    <div class="h-80 md:h-full bg-gray-100 flex items-center justify-center">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-gray-400">Tidak ada gambar</span>
                        @endif
                    </div>

                    <div class="p-6">
                        <p class="text-sm text-green-600 uppercase font-semibold">
                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                        </p>
                        <h1 class="text-2xl font-bold text-gray-800 mt-2">
                            {{ $product->name }}
                        </h1>

                        <p class="text-3xl font-bold text-green-600 mt-4">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        @if ($product->description)
                            <div class="mt-4">
                                <h3 class="font-semibold text-gray-700 mb-2">Deskripsi</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                            </div>
                        @endif

                        @if ($product->sizes->count() > 0)
                            <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-6">
                                @csrf

                                <label class="block text-gray-700 font-semibold mb-2">Pilih Size</label>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @foreach ($product->sizes as $productSize)
                                        <label class="size-option">
                                            <input
                                                type="radio"
                                                name="size_id"
                                                value="{{ $productSize->id }}"
                                                class="hidden peer"
                                                {{ $productSize->stock <= 0 ? 'disabled' : '' }}
                                                required
                                            >
                                            <span class="block px-4 py-2 rounded-lg border-2 cursor-pointer text-sm font-medium
                                                peer-checked:border-green-600 peer-checked:bg-green-50 peer-checked:text-green-700
                                                {{ $productSize->stock <= 0 ? 'border-gray-200 text-gray-300 cursor-not-allowed line-through' : 'border-gray-300 text-gray-700 hover:border-green-400' }}">
                                                {{ $productSize->size }}
                                                @if ($productSize->stock <= 0)
                                                    (Habis)
                                                @endif
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
                                    Tambah ke Keranjang
                                </button>
                            </form>
                        @else
                            <div class="mt-6 bg-red-50 text-red-600 px-4 py-3 rounded-lg text-center font-medium">
                                Stok Habis
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>