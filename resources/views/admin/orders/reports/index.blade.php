<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Penjualan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Filter periode --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 flex flex-wrap items-center gap-3">
                @foreach (['7' => '7 Hari', '30' => '30 Hari', '90' => '90 Hari', 'month' => 'Bulan Ini'] as $key => $label)
                    <a href="{{ route('admin.reports.index', ['period' => $key]) }}"
                        class="px-4 py-2 rounded-full text-sm font-medium {{ $period === (string) $key ? 'bg-green-600 text-white' : 'bg-green-50 text-green-700 border border-green-200 hover:bg-green-100' }}">
                        {{ $label }}
                    </a>
                @endforeach

                <form action="{{ route('admin.reports.index') }}" method="GET" class="flex items-center gap-2 ml-auto">
                    <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-lg border-gray-300 text-sm">
                    <span class="text-gray-400">-</span>
                    <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-lg border-gray-300 text-sm">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">Terapkan</button>
                </form>
            </div>

            <p class="text-sm text-gray-500">
                Periode: {{ $from->format('d M Y') }} - {{ $to->format('d M Y') }} (hanya pesanan berstatus Paid)
            </p>

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Pesanan Lunas</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $orderCount }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Barang Terjual</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $itemsSold }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <p class="text-gray-500 text-sm">Rata-rata per Pesanan</p>
                    <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($avgOrder, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Grafik pendapatan harian --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Pendapatan per Hari</h3>
                <div class="h-72"><canvas id="revenueChart"></canvas></div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Produk terlaris --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Produk Terlaris (Top 5)</h3>
                    @forelse ($topProducts as $i => $row)
                        <div class="flex justify-between items-center border-b border-gray-100 py-2">
                            <span class="text-gray-700">{{ $i + 1 }}. {{ $row->name }}</span>
                            <span class="text-sm text-gray-500">
                                {{ $row->qty }} pcs ·
                                <span class="text-green-600 font-semibold">Rp {{ number_format($row->revenue, 0, ',', '.') }}</span>
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada penjualan di periode ini.</p>
                    @endforelse
                </div>

                {{-- Per kategori --}}
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Penjualan per Kategori</h3>
                    @if ($byCategory->isNotEmpty())
                        <div class="h-64"><canvas id="categoryChart"></canvas></div>
                    @else
                        <p class="text-gray-500">Belum ada penjualan di periode ini.</p>
                    @endif
                </div>
            </div>

            {{-- Per warna --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Penjualan per Warna</h3>
                @forelse ($byColor as $row)
                    <div class="flex justify-between items-center border-b border-gray-100 py-2">
                        <span class="text-gray-700">{{ $row->name }}</span>
                        <span class="text-sm text-gray-500">
                            {{ $row->qty }} pcs ·
                            <span class="text-green-600 font-semibold">Rp {{ number_format($row->revenue, 0, ',', '.') }}</span>
                        </span>
                    </div>
                @empty
                    <p class="text-gray-500">Belum ada penjualan di periode ini.</p>
                @endforelse
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const rupiah = v => 'Rp ' + Number(v).toLocaleString('id-ID');

        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($values),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22, 163, 74, 0.12)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { tooltip: { callbacks: { label: ctx => rupiah(ctx.parsed.y) } } },
                scales: { y: { beginAtZero: true, ticks: { callback: v => rupiah(v) } } }
            }
        });

        @if ($byCategory->isNotEmpty())
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: @json($byCategory->pluck('name')),
                datasets: [{
                    data: @json($byCategory->pluck('revenue')->map(fn ($v) => (int) $v)),
                    backgroundColor: ['#16a34a', '#facc15', '#3b82f6', '#f97316', '#a855f7', '#14b8a6'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { tooltip: { callbacks: { label: ctx => ctx.label + ': ' + rupiah(ctx.parsed) } } }
            }
        });
        @endif
    </script>
</x-app-layout>