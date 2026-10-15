<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Riwayat Penjualan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('sales.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg inline-block font-medium">
                    + Catat Penjualan Manual
                </a>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Produk</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Qty</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Total</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Sumber</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="px-4 py-3 text-sm font-semibold text-gray-600"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            <tr class="border-b border-gray-100">
                                <td class="px-4 py-3 text-gray-800">{{ $sale->product->name ?? 'Produk telah dihapus' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $sale->quantity }}</td>
                                <td class="px-4 py-3 text-green-600 font-semibold">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">
                                    @if ($sale->order_id)
                                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full font-medium">Online</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded-full font-medium">Manual</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-sm">{{ $sale->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3 text-right">
<form id="delete-form-sale-{{ $sale->id }}" action="{{ route('sales.destroy', $sale) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<button type="button" onclick="confirmDeleteSale({{ $sale->id }})" class="text-sm text-red-600 hover:text-red-700 font-medium">
    Hapus
</button>                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada penjualan tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout> 
<script>
    function confirmDeleteSale(id) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data penjualan yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-sale-' + id).submit();
            }
        });
    }
</script>