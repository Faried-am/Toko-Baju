<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Produk</h2>
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

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Kategori</label>
                        <select name="category_id" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Warna, Size & Stok</label>
                        <div id="sizes-wrapper">
                            <div class="flex gap-2 mb-2 size-row">
                                <input type="text" name="sizes[0][color]" placeholder="Warna" class="flex-1 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                                <input type="text" name="sizes[0][size]" placeholder="Size" class="w-24 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                                <input type="number" name="sizes[0][stock]" placeholder="Stok" min="0" class="w-28 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>
                        <button type="button" id="add-size" class="text-green-600 text-sm font-medium mt-1">+ Tambah Varian</button>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" rows="3" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Harga</label>
                        <input type="number" name="price" value="{{ old('price') }}" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Gambar Utama</label>
                        <input type="file" name="image" class="w-full text-gray-700">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Galeri per Warna (opsional)</label>
                        <div id="gallery-container" class="space-y-2"></div>
                        <button type="button" onclick="addGalleryRow()" class="mt-2 text-sm text-green-600 hover:text-green-700 font-medium">
                            + Tambah Gambar
                        </button>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 5 gambar. Isi warna persis seperti di varian (misal: Biru).</p>
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                        Simpan Produk
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        let sizeIndex = 1;
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