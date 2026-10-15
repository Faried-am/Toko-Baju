<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Pesanan
        </h2>
    </x-slot>

    <style>
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; position: relative; }
        .star-rating input { position: absolute; opacity: 0; pointer-events: none; }
        .star-rating label { font-size: 1.5rem; color: #d1d5db; cursor: pointer; line-height: 1; padding-right: 2px; }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label { color: #facc15; }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">{{ session('error') }}</div>
            @endif

            @if ($orders->isEmpty())
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 text-center text-gray-500">
                    Belum ada pesanan.
                    <a href="{{ route('shop.index') }}" class="text-green-600 font-medium">Mulai belanja</a>
                </div>
            @endif

            @foreach ($orders as $order)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <div class="flex justify-between items-start mb-4 pb-4 border-b border-gray-100">
                        <div>
                            <p class="font-bold text-gray-800">{{ $order->order_number }}</p>
                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <span @class([
                            'px-3 py-1 rounded-full text-xs font-medium',
                            'bg-yellow-100 text-yellow-700' => $order->status === 'pending',
                            'bg-green-100 text-green-700' => $order->status === 'paid',
                            'bg-red-100 text-red-700' => in_array($order->status, ['expired', 'cancelled']),
                        ])>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <div class="space-y-4 mb-4">
                        @foreach ($order->items as $item)
                            <div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-700">
                                        {{ $item->product->name ?? 'Produk telah dihapus' }}
                                        @if ($item->size)
                                            ({{ $item->size->color ? $item->size->color . ', ' : '' }}Size {{ $item->size->size }})
                                        @endif
                                        × {{ $item->quantity }}
                                    </span>
                                    <span class="text-gray-800 font-medium">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>

                                @if ($order->status === 'paid' && $item->product)
                                    @if ($order->shipping_status === 'completed')
                                        @php $given = $myReviews[$item->product_id] ?? 0; @endphp
                                        <form action="{{ route('reviews.store', $item->product_id) }}" method="POST" class="mt-1">
                                            @csrf
                                            <p class="text-xs text-gray-500">
                                                {{ $given ? 'Rating kamu (klik untuk mengubah):' : 'Beri rating produk ini:' }}
                                            </p>
                                            <div class="star-rating">
                                                @for ($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating"
                                                        id="star-{{ $item->id }}-{{ $i }}"
                                                        value="{{ $i }}"
                                                        onchange="this.form.submit()"
                                                        @checked($given == $i)>
                                                    <label for="star-{{ $item->id }}-{{ $i }}" title="{{ $i }} bintang">★</label>
                                                @endfor
                                            </div>
                                        </form>
                                    @else
                                        <p class="text-xs text-gray-400 mt-1">Rating bisa diberikan setelah pesanan diterima.</p>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if ($order->address)
                        <p class="text-xs text-gray-500 mb-3">
                            Dikirim ke: {{ $order->recipient_name }}, {{ $order->address }}, {{ $order->city }} {{ $order->postal_code }}
                        </p>
                    @endif

                    @if ($order->status === 'paid')
                        @php
                            $shipLabels = [
                                'pending' => ['Menunggu diproses', 'bg-yellow-100 text-yellow-700'],
                                'processing' => ['Sedang diproses', 'bg-blue-100 text-blue-700'],
                                'shipped' => ['Dalam pengiriman', 'bg-purple-100 text-purple-700'],
                                'completed' => ['Selesai', 'bg-green-100 text-green-700'],
                            ];
                            [$shipText, $shipClass] = $shipLabels[$order->shipping_status] ?? $shipLabels['pending'];
                        @endphp

                        <div class="mb-3 text-sm">
                            <p class="mb-1">
                                <span class="text-gray-500">Pengiriman:</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $shipClass }}">{{ $shipText }}</span>
                            </p>

                            @if ($order->courier && $order->tracking_number)
                                <p class="text-gray-600 mb-1">Kurir: {{ $order->courier }} · Resi: <span class="font-mono">{{ $order->tracking_number }}</span></p>
                            @endif

                            @if ($order->shipping_status === 'shipped')
                                <form action="{{ route('orders.received', $order) }}" method="POST" onsubmit="return confirm('Pesanan sudah kamu terima?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="mt-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium">
                                        Pesanan Diterima
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif

                    <div class="flex justify-between pt-4 border-t border-gray-100">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="font-bold text-green-600">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</x-app-layout>