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

            <!-- Alamat Pengiriman -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-gray-800 mb-3">Alamat Pengiriman</h3>
    @if ($order->address)
        <div class="text-sm space-y-1">
            <p class="text-gray-800 font-medium">{{ $order->recipient_name }} · {{ $order->phone }}</p>
            <p class="text-gray-600">{{ $order->address }}</p>
            <p class="text-gray-600">{{ $order->city }} {{ $order->postal_code }}</p>
            @if ($order->note)
                <p class="text-gray-500">Catatan: {{ $order->note }}</p>
            @endif
        </div>
    @else
        <p class="text-sm text-gray-400">Pesanan lama, tidak ada data alamat.</p>
    @endif
</div>

<!-- Status Pengiriman -->
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
    <h3 class="font-bold text-gray-800 mb-3">Status Pengiriman</h3>

    @if (session('success'))
        <div class="mb-3 bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-lg text-sm">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-3 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-3 bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-sm">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if ($order->status === 'paid')
<form action="{{ route('admin.orders.shipping', $order) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-4">
        <label class="block text-sm text-gray-700 mb-1">Status</label>
        <select name="shipping_status" class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
            @foreach (['pending' => 'Menunggu diproses', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'completed' => 'Selesai'] as $value => $label)
                <option value="{{ $value }}" @selected(old('shipping_status', $order->shipping_status) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-sm text-gray-700 mb-1">Kurir</label>
        <input type="text" name="courier" placeholder="JNE, J&T, SiCepat..."
            value="{{ old('courier', $order->courier) }}"
            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
    </div>

    <div class="mb-4">
        <label class="block text-sm text-gray-700 mb-1">Nomor Resi</label>
        <input type="text" name="tracking_number"
            value="{{ old('tracking_number', $order->tracking_number) }}"
            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
    </div>

    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium mt-2">
        Simpan Status
    </button>
</form>

        <div class="text-xs text-gray-500 mt-3 space-y-0.5">
            @if ($order->shipped_at)
                <p>Dikirim: {{ $order->shipped_at->format('d M Y, H:i') }}</p>
            @endif
            @if ($order->completed_at)
                <p>Selesai: {{ $order->completed_at->format('d M Y, H:i') }}</p>
            @endif
        </div>
    @else
        <p class="text-sm text-gray-400">Pesanan belum dibayar, status pengiriman belum bisa diatur.</p>
    @endif
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
{{ $item->size->color ? $item->size->color . ', ' : '' }}Size {{ $item->size->size }} ×                  
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