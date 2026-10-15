<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pesanan Berhasil
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 text-center">

                <svg class="w-16 h-16 text-green-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <h3 class="text-xl font-bold text-gray-800 mb-2">Pesanan Berhasil Dibuat!</h3>
                <p class="text-gray-500 mb-4">Nomor Pesanan: <span class="font-semibold text-gray-700">{{ $order->order_number }}</span></p>
                <p class="text-2xl font-bold text-green-600 mb-6">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>

                <a href="{{ route('shop.index') }}" class="text-green-600 hover:text-green-700 font-medium">
                    ← Kembali Belanja
                </a>
            </div>
        </div>
    </div>
</x-app-layout>