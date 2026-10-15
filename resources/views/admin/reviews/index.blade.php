<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rating Produk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif

            @forelse ($products as $product)
                <details class="bg-white border border-gray-200 rounded-xl shadow-sm">
                    <summary class="flex items-center justify-between p-4 cursor-pointer">
                        <span class="font-semibold text-gray-800">{{ $product->name }}</span>
                        <span class="text-yellow-500 font-semibold">
                            ★ {{ number_format($product->reviews_avg_rating, 1) }}
                            <span class="text-gray-400 text-sm font-normal">({{ $product->reviews_count }} rating)</span>
                        </span>
                    </summary>

                    <div class="border-t border-gray-100 divide-y divide-gray-100">
                        @foreach ($product->reviews as $review)
                            <div class="flex items-center justify-between px-4 py-3">
                                <div>
                                    <p class="text-gray-800">{{ $review->user->name ?? 'Pengguna' }}</p>
                                    <p class="text-xs text-gray-400">{{ $review->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-yellow-500">
                                        {{ str_repeat('★', $review->rating) }}<span class="text-gray-300">{{ str_repeat('★', 5 - $review->rating) }}</span>
                                    </span>
                                    <form action="{{ route('reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Hapus rating ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-700">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </details>
            @empty
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 text-center text-gray-500">
                    Belum ada rating dari customer.
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>