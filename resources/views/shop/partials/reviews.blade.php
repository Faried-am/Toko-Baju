<style>
    .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; }
    .star-rating input { position: absolute; opacity: 0; pointer-events: none; }
    .star-rating label { font-size: 2rem; color: #d1d5db; cursor: pointer; line-height: 1; padding-right: 2px; }
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label { color: #facc15; }
</style>

@php
    $count = $product->reviews->count();
    $avg = $product->reviews->avg('rating');
@endphp

<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 space-y-4">

    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800">Rating Pembeli</h3>
        @if ($count > 0)
            <p class="text-yellow-500 font-semibold">
                ★ {{ number_format($avg, 1) }}
                <span class="text-gray-400 text-sm font-normal">({{ $count }} rating)</span>
            </p>
        @else
            <p class="text-sm text-gray-400">Belum ada rating</p>
        @endif
    </div>

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">{{ session('error') }}</div>
    @endif

    @if ($errors->has('rating'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            Pilih rating bintang 1 sampai 5.
        </div>
    @endif

    @if ($canReview)
        <form action="{{ route('reviews.store', $product) }}" method="POST" class="border border-gray-100 rounded-lg p-4 bg-gray-50 space-y-3">
            @csrf
            <p class="font-medium text-gray-700">{{ $myReview ? 'Ubah rating kamu' : 'Beri rating produk ini' }}</p>

            <div class="star-rating">
                @for ($i = 5; $i >= 1; $i--)
                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" @checked(old('rating', $myReview->rating ?? 0) == $i)>
                    <label for="star{{ $i }}" title="{{ $i }} bintang">★</label>
                @endfor
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                {{ $myReview ? 'Perbarui Rating' : 'Kirim Rating' }}
            </button>
        </form>

        @if ($myReview)
            <form action="{{ route('reviews.destroy', $myReview) }}" method="POST" onsubmit="return confirm('Hapus rating kamu?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-xs text-red-600 hover:text-red-700">Hapus rating saya</button>
            </form>
        @endif
    @else
        <p class="text-sm text-gray-500">Rating hanya bisa diberikan oleh pembeli yang pesanannya sudah dibayar.</p>
    @endif
</div>