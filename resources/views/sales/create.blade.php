<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Catat Penjualan
        </h2>
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

                <form action="{{ route('sales.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Produk</label>
                        <select name="product_id" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} (Stok: {{ $product->stock }}) - Rp {{ number_format($product->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Jumlah Terjual</label>
                        <input type="number" name="quantity" min="1" class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                        Simpan Penjualan
                    </button>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>