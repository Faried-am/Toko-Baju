<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Toko Baju
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Search bar --}}
<input
    type="text"
    id="search-input"
    value="{{ $searchTerm }}"
    placeholder="Cari produk... (misal: kemeja)"
    autocomplete="off"
    class="w-full rounded-lg border-gray-300 focus:ring-green-500 focus:border-green-500"
>

{{-- Filter harga --}}
<div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-wrap items-center gap-3">
    <label class="text-sm font-medium text-gray-700">Rentang Harga:</label>
    <input
        type="number"
        id="min-price"
        value="{{ $minPrice }}"
        placeholder="Harga min"
        class="w-32 rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500"
    >
    <span class="text-gray-400">-</span>
    <input
        type="number"
        id="max-price"
        value="{{ $maxPrice }}"
        placeholder="Harga maks"
        class="w-32 rounded-lg border-gray-300 text-sm focus:ring-green-500 focus:border-green-500"
    >
    <button type="button" id="reset-price" class="text-sm text-gray-500 hover:text-red-600">
        Reset
    </button>
</div>
            {{-- Area yang akan di-update otomatis --}}
            <div id="product-results">
                @include('shop.partials.product-grid')
            </div>

        </div>
    </div>

<script>
    let currentCategory = '{{ $selectedCategory }}';
    let searchTimer;

    function loadProducts() {
        const search = document.getElementById('search-input').value;
        const minPrice = document.getElementById('min-price').value;
        const maxPrice = document.getElementById('max-price').value;

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (currentCategory) params.append('category', currentCategory);
        if (minPrice) params.append('min_price', minPrice);
        if (maxPrice) params.append('max_price', maxPrice);

        fetch('{{ route('shop.index') }}?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById('product-results').innerHTML = html;
        });

        const newUrl = '{{ route('shop.index') }}?' + params.toString();
        window.history.pushState({}, '', newUrl);
    }

    document.getElementById('search-input').addEventListener('keyup', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(loadProducts, 400);
    });

    document.getElementById('min-price').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(loadProducts, 500);
    });

    document.getElementById('max-price').addEventListener('input', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(loadProducts, 500);
    });

    document.getElementById('reset-price').addEventListener('click', function () {
        document.getElementById('min-price').value = '';
        document.getElementById('max-price').value = '';
        loadProducts();
    });

    document.querySelectorAll('.category-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            currentCategory = this.dataset.category;

            document.querySelectorAll('.category-btn').forEach(b => {
                b.classList.remove('bg-green-600', 'text-white');
                b.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            });
            this.classList.add('bg-green-600', 'text-white');
            this.classList.remove('bg-green-50', 'text-green-700', 'border', 'border-green-200');

            loadProducts();
        });
    });
</script>
</x-app-layout>

