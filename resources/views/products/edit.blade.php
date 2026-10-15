<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Produk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">

                @if ($errors->any())
                    <div class="bg-red-50 text-red-700 border border-red-200 p-4 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($product->category_id == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Warna, Size & Stok</label>
                        <div id="sizes-wrapper">
                            @foreach ($product->sizes as $i => $productSize)
                                <div class="flex gap-2 mb-2 size-row">
                                    <input type="hidden" name="sizes[{{ $i }}][id]" value="{{ $productSize->id }}">
                                    <input type="text" name="sizes[{{ $i }}][color]" value="{{ $productSize->color }}" placeholder="Warna" class="flex-1 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                                    <input type="text" name="sizes[{{ $i }}][size]" value="{{ $productSize->size }}" placeholder="Size" class="w-24 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                                    <input type="number" name="sizes[{{ $i }}][stock]" value="{{ $productSize->stock }}" placeholder="Stok" min="0" class="w-28 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 px-2">✕</button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" id="add-size" class="text-green-600 text-sm font-medium mt-1">+ Tambah Varian</button>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Harga</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                    </div>

                    @if ($product->image)
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-1">Gambar Utama Saat Ini</label>
                            <img src="{{ asset('storage/' . $product->image) }}" class="h-32 rounded-lg border border-gray-200">
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Ganti Gambar Utama (opsional)</label>
                        <input type="file" name="image" class="w-full text-gray-700">
                    </div>

                    @if ($product->images->isNotEmpty())
                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">Galeri Saat Ini (isi warna tiap gambar)</label>
                            <div class="flex flex-wrap gap-4">
                                @foreach ($product->images as $img)
                                    <div class="relative w-28">
                                        <img src="{{ asset('storage/' . $img->image) }}" class="h-24 w-28 object-cover rounded-lg border border-gray-200">
                                        <button type="submit" form="delete-image-{{ $img->id }}"
                                            onclick="return confirm('Hapus gambar ini?')"
                                            class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full h-6 w-6 text-xs">✕</button>
                                        <input type="text" name="existing_images[{{ $img->id }}][color]" value="{{ $img->color }}" placeholder="Warna"
                                            class="mt-1 w-full text-xs rounded border-gray-300 focus:ring-green-500 focus:border-green-500">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Tambah Gambar Galeri (opsional)</label>
                        <div id="gallery-container" class="space-y-2"></div>
                        <button type="button" onclick="addGalleryRow()" class="mt-2 text-sm text-green-600 hover:text-green-700 font-medium">
                            + Tambah Gambar
                        </button>
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                        Update Produk
                    </button>
                </form>

                {{-- Form hapus gambar: di luar form utama --}}
                @foreach ($product->images as $img)
                    <form id="delete-image-{{ $img->id }}" action="{{ route('products.images.destroy', $img) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                @endforeach

            </div>
        </div>
    </div>

    <script>
        let sizeIndex = {{ $product->sizes->count() }};
        let galleryIndex = 0;

        document.getElementById('add-size').addEventListener('click', function () {
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2 size-row';
            div.innerHTML = `
                <input type="text" name="sizes[${sizeIndex}][color]" placeholder="Warna" class="flex-1 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                <input type="text" name="sizes[${sizeIndex}][size]" placeholder="Size" class="w-24 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                <input type="number" name="sizes[${sizeIndex}][stock]" placeholder="Stok" min="0" class="w-28 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 px-2">✕</button>
            `;
            document.getElementById('sizes-wrapper').appendChild(div);
            sizeIndex++;
        });

        function addGalleryRow() {
            if (document.querySelectorAll('.gallery-row').length >= 5) {
                alert('Maksimal 5 gambar galeri.');
                return;
            }
            const row = document.createElement('div');
            row.className = 'flex gap-2 items-center gallery-row';
            row.innerHTML = `
                <input type="file" name="gallery[${galleryIndex}][file]" accept="image/*" class="flex-1 text-sm text-gray-700">
                <input type="text" name="gallery[${galleryIndex}][color]" placeholder="Warna" class="w-32 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 px-2">✕</button>
            `;
            document.getElementById('gallery-container').appendChild(row);
            galleryIndex++;
        }
    </script>
</x-app-layout>