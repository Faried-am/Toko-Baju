<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pesanan Customer
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">No. Pesanan</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Customer</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Produk</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Total</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.orders.show', $order->id) }}'">                                <td class="px-4 py-3 text-gray-800 font-medium">{{ $order->order_number }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $order->user->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-600 text-sm">
                                    @foreach ($order->items as $item)
                                        {{ $item->product->name ?? 'Produk dihapus' }} × {{ $item->quantity }}@if (!$loop->last), @endif
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-green-600 font-semibold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if ($order->status === 'paid')
                                        <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">Lunas</span>
                                    @elseif ($order->status === 'pending')
                                        <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full font-medium">Menunggu</span>
                                    @elseif ($order->status === 'expired')
                                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full font-medium">Kadaluwarsa</span>
                                    @else
                                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full font-medium">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-sm">{{ $order->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada pesanan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>