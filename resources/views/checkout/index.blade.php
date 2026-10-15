<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Checkout
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-50 text-red-700 border border-red-200 p-4 rounded-lg mb-4">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6">
                <h3 class="font-bold text-gray-800 mb-4">Ringkasan Pesanan</h3>

                @foreach ($cart as $item)
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-gray-700">
                            {{ $item['name'] }}
                            @if (!empty($item['color']) || !empty($item['size']))
                                <span class="text-sm text-gray-500">
                                    ({{ !empty($item['color']) ? $item['color'] . ', ' : '' }}Size {{ $item['size'] ?? '-' }})
                                </span>
                            @endif
                            × {{ $item['quantity'] }}
                        </span>
                        <span class="text-gray-800 font-medium">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                    </div>
                @endforeach

                <div class="flex justify-between pt-4 mt-2">
                    <span class="font-bold text-gray-800">Total</span>
                    <span class="font-bold text-green-600 text-xl">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mb-6 space-y-4">
                    <h3 class="font-bold text-gray-800">Alamat Pengiriman</h3>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Nama Penerima</label>
                        <input type="text" name="recipient_name"
                            value="{{ old('recipient_name', $lastOrder->recipient_name ?? auth()->user()->name) }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Nomor HP</label>
                        <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                            value="{{ old('phone', $lastOrder->phone ?? '') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="address" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">{{ old('address', $lastOrder->address ?? '') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Kota / Kabupaten</label>
                            <input type="text" name="city"
                                value="{{ old('city', $lastOrder->city ?? '') }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" name="postal_code"
                                value="{{ old('postal_code', $lastOrder->postal_code ?? '') }}"
                                class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-700 mb-1">Catatan untuk Kurir (opsional)</label>
                        <input type="text" name="note" placeholder="Contoh: titip ke satpam"
                            value="{{ old('note') }}"
                            class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg font-medium">
                    Buat Pesanan
                </button>
            </form>

        </div>
    </div>
</x-app-layout>