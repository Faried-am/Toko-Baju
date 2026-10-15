<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Kategori</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-3">Tambah Kategori</h3>
                <form action="{{ route('categories.store') }}" method="POST" class="flex gap-2">
                    @csrf
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama kategori (misal: Celana Pendek)"
                        class="flex-1 rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">Tambah</button>
                </form>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Nama Kategori</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Jumlah Produk</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-3 text-gray-800 font-medium">{{ $category->name }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $category->products_count }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex justify-end items-center gap-3">
                                        <a href="{{ route('categories.edit', $category) }}" class="text-sm text-green-600 hover:text-green-700 font-medium">Edit</a>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                            onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-6 text-center text-gray-500">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>