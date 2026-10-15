<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Pesanan
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-green-600 font-medium hover:underline">
                ← Kembali ke Daftar Pesanan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Info Utama -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <div class="flex justify-between items-start mb-4 pb-4 border-b border-gray-100">
                    <div>
                        <p class="text-xl font-bold text-gray-800">{{ $order->order_number }}</p>
                        <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if ($order->status === 'paid')
                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">Lunas</span>
                    @elseif ($order->status === 'pending')
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-medium">Menunggu</span>
                    @elseif ($order->status === 'expired')
                        <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full font-medium">Kadaluwarsa</span>
                    @else
                        <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-medium">Dibatalkan</span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Customer</p>
                        <p class="text-gray-800 font-medium">{{ $order->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Email</p>
                        <p class="text-gray-800 font-medium">{{ $order->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Rincian Item -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-4">Rincian Produk</h3>

<div class="space-y-3">
    @foreach ($order->items as $item)
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
            <div>
                <p class="text-gray-800 font-medium">
                    {{ $item->product->name ?? 'Produk telah dihapus' }}
                </p>
                <p class="text-xs text-gray-500">
                    @if ($item->size)
                        Size {{ $item->size->size }} ×
                    @endif
                    {{ $item->quantity }} pcs (Rp {{ number_format($item->price, 0, ',', '.') }} / pcs)
                </p>
            </div>
            <p class="text-gray-800 font-semibold">
                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
            </p>
        </div>
    @endforeach
</div>
</x-app-layout>