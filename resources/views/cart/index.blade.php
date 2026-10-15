<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Keranjang Belanja
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                    @forelse ($cart as $cartKey => $item)
                    <div class="flex items-center justify-between p-4 border-b border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                @if ($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover">
                                @else
                                    <span class="text-gray-400 text-xs">No Image</span>
                                @endif
                            </div>
                            <div>
                            <p class="font-semibold text-gray-800">{{ $item['name'] }}</p>
<p class="text-xs text-gray-400">
    @if (!empty($item['color']))
        Warna: {{ $item['color'] }} ·
    @endif
    Size: {{ $item['size'] }}
</p>                            <p class="text-sm text-gray-500">{{ $item['quantity'] }} × Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <p class="font-bold text-green-600">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</p>

<form action="{{ route('cart.remove', $cartKey) }}" method="POST">                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-600 hover:text-red-700">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 p-6 text-center">Keranjang masih kosong.</p>
                @endforelse

            </div>

            @if (count($cart) > 0)
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 mt-6 flex justify-between items-center">
                    <p class="text-lg font-bold text-gray-800">Total</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</p>
                </div>

<div class="mt-6 flex justify-between items-center">
    <a href="{{ route('shop.index') }}" class="text-green-600 hover:text-green-700 font-medium">
        ← Lanjut Belanja
    </a>

    <a href="{{ route('checkout.index') }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-medium">
        Checkout →
    </a>
</div>            @endif

        </div>
    </div>
</x-app-layout>
