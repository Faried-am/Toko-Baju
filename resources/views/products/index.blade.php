<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Daftar Produk
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('products.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-block font-medium">
                    + Tambah Produk
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                @forelse ($products as $product)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition">
                        
                        <div class="h-48 bg-gray-100 flex items-center justify-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-gray-400">Tidak ada gambar</span>
                            @endif
                        </div>

                        <div class="p-4">
                            <p class="text-xs text-green-600 uppercase font-semibold">
                                {{ $product->category->name ?? 'Tanpa Kategori' }}
                            </p>
                            <h3 class="text-lg font-bold text-gray-800 mt-1">
                                {{ $product->name }}
                            </h3>
<div class="mt-1 space-y-0.5">
    @forelse ($product->sizes->groupBy(fn ($s) => $s->color ?: 'Standar') as $color => $variants)
        <p class="text-sm text-gray-500">
            <span class="font-medium text-gray-700">{{ $color }}:</span>
            {{ $variants->pluck('size')->join(', ') }}
            <span class="text-xs text-gray-400">(stok {{ $variants->sum('stock') }})</span>
        </p>
    @empty
        <p class="text-sm text-gray-400">Belum ada size</p>
    @endforelse
</div>

<p class="text-xl font-bold text-green-600 mt-2">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                Stok: {{ $product->stock }}
                            </p>

                            <div class="flex gap-2 mt-3">
                                <a href="{{ route('products.edit', $product) }}" class="text-sm bg-green-50 hover:bg-green-100 text-green-700 px-3 py-1 rounded border border-green-200">
                                    Edit
                                </a>

<form id="delete-form-product-{{ $product->id }}" action="{{ route('products.destroy', $product) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<button type="button" onclick="confirmDeleteProduct({{ $product->id }})" class="text-sm bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1 rounded border border-red-200">
    Hapus
</button>                            </div>
                        </div>

                    </div>
                @empty
                    <p class="text-gray-500 col-span-4">Belum ada produk.</p>
                @endforelse

            </div>

        </div>
    </div>
</x-app-layout>
<script>
    function confirmDeleteProduct(id) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Produk yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-product-' + id).submit();
            }
        });
    }
</script>