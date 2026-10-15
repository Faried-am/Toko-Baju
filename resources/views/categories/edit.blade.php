<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kategori</h2>
            <a href="{{ route('categories.index') }}" class="text-sm text-green-600 font-medium hover:underline">← Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
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

                <form action="{{ route('categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                            class="w-full rounded-lg border-gray-300 text-gray-800 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">Update Kategori</button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>