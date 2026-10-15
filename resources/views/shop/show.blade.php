<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Produk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                @php
                    $galleryItems = collect();
                    if ($product->image) {
                        $galleryItems->push(['path' => $product->image, 'color' => '']);
                    }
                    foreach ($product->images as $img) {
                        $galleryItems->push(['path' => $img->image, 'color' => $img->color ?? '']);
                    }
                @endphp

                <div>
                    <div class="h-80 bg-gray-100 flex items-center justify-center">
                        @if ($galleryItems->isNotEmpty())
                            <img id="main-image" src="{{ asset('storage/' . $galleryItems->first()['path']) }}" alt="{{ $product->name }}" class="h-full object-contain">
                        @else
                            <span class="text-gray-400">Tidak ada gambar</span>
                        @endif
                    </div>

                    @if ($galleryItems->count() > 1)
                        <div class="flex gap-2 p-3 bg-white border-t border-gray-100 overflow-x-auto">
                            @foreach ($galleryItems as $i => $item)
                                <img
                                    src="{{ asset('storage/' . $item['path']) }}"
                                    data-color="{{ strtolower(trim($item['color'])) }}"
                                    alt="Gambar {{ $i + 1 }}"
                                    class="thumb h-16 w-16 object-cover rounded-lg border-2 cursor-pointer hover:border-green-400 {{ $i === 0 ? 'border-green-600' : 'border-transparent' }}"
                                >
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="p-6 space-y-4">
                    <span class="text-xs font-semibold text-green-600 uppercase">{{ $product->category->name ?? '' }}</span>

                    <h1 class="text-2xl font-bold text-gray-800">{{ $product->name }}</h1>

                    <p class="text-xl font-semibold text-green-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>

                    @if ($product->description)
                        <p class="text-gray-600">{{ $product->description }}</p>
                    @endif

                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-4">
                        @csrf

                        @if ($product->sizes && $product->sizes->count() > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Warna & Size</label>

                                @foreach ($product->sizes->groupBy(fn ($s) => $s->color ?: 'Standar') as $color => $variants)
                                    <div class="mb-3">
                                        <p class="text-sm font-medium text-gray-700 mb-2">{{ $color }}</p>
                                        <div class="flex flex-wrap gap-3">
                                            @foreach ($variants as $size)
                                                <label class="cursor-pointer">
                                                    <input
                                                        type="radio"
                                                        name="size_id"
                                                        value="{{ $size->id }}"
                                                        data-color="{{ strtolower(trim($size->color ?? '')) }}"
                                                        class="peer sr-only"
                                                        {{ $size->stock <= 0 ? 'disabled' : '' }}
                                                        required
                                                    >
                                                    <span class="px-4 py-2 rounded-lg border text-sm font-medium flex flex-col items-center min-w-[64px]
                                                        {{ $size->stock <= 0
                                                            ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed'
                                                            : 'bg-white text-gray-700 border-gray-300 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 hover:border-green-400' }}">
                                                        <span class="{{ $size->stock <= 0 ? 'line-through' : '' }}">{{ $size->size }}</span>
                                                        <span class="text-[11px] mt-0.5 font-normal {{ $size->stock <= 0 ? 'text-gray-400' : 'opacity-80' }}">
                                                            {{ $size->stock <= 0 ? 'Habis' : ($size->stock <= 5 ? 'Sisa ' . $size->stock : 'Stok ' . $size->stock) }}
                                                        </span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Stok untuk produk ini belum tersedia.</p>
                        @endif

                        <button
                            type="submit"
                            class="w-full bg-green-600 text-white py-2 rounded-lg font-medium hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed"
                            {{ (!$product->sizes || $product->sizes->count() === 0) ? 'disabled' : '' }}
                        >
                            Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="pb-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @include('shop.partials.reviews')
        </div>
    </div>
    <script>
        const mainImage = document.getElementById('main-image');
        const thumbs = Array.from(document.querySelectorAll('.thumb'));

        function selectThumb(t) {
            mainImage.src = t.src;
            thumbs.forEach(x => x.classList.remove('border-green-600'));
            t.classList.add('border-green-600');
        }

        thumbs.forEach(t => t.addEventListener('click', () => selectThumb(t)));

        function applyColor(color) {
            if (!mainImage || thumbs.length === 0) return;

            let matching = thumbs.filter(t => color !== '' && t.dataset.color === color);
            if (matching.length === 0) {
                matching = thumbs.filter(t => t.dataset.color === '');
            }
            if (matching.length === 0) {
                matching = thumbs;
            }

            thumbs.forEach(t => t.classList.toggle('hidden', !matching.includes(t)));
            selectThumb(matching[0]);
        }

        document.querySelectorAll('input[name="size_id"]').forEach(r => {
            r.addEventListener('change', () => applyColor(r.dataset.color || ''));
        });
    </script>
</x-app-layout>