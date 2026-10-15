<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to, $period] = $this->resolvePeriod($request);

        $lineTotal = DB::raw('order_items.price * order_items.quantity');

        $items = fn () => OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'paid')
            ->whereBetween('orders.created_at', [$from, $to]);

        $paidOrders = Order::where('status', 'paid')->whereBetween('created_at', [$from, $to]);

        $totalRevenue = (clone $paidOrders)->sum('total_price');
        $orderCount = (clone $paidOrders)->count();
        $itemsSold = (int) $items()->sum('order_items.quantity');
        $avgOrder = $orderCount > 0 ? $totalRevenue / $orderCount : 0;

        // Pendapatan per hari (hari tanpa penjualan diisi 0)
        $daily = (clone $paidOrders)
            ->selectRaw('DATE(created_at) as d, SUM(total_price) as total')
            ->groupBy('d')
            ->pluck('total', 'd');

        $labels = [];
        $values = [];
        foreach (CarbonPeriod::create($from->copy()->startOfDay(), $to->copy()->startOfDay()) as $day) {
            $labels[] = $day->format('d M');
            $values[] = (int) ($daily[$day->format('Y-m-d')] ?? 0);
        }

        $topProducts = $items()
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->selectRaw("COALESCE(products.name, 'Produk dihapus') as name, SUM(order_items.quantity) as qty, SUM(order_items.price * order_items.quantity) as revenue")
            ->groupBy('order_items.product_id', 'products.name')
            ->orderByDesc('qty')
            ->limit(5)
            ->get();

        $byCategory = $items()
            ->leftJoin('products', 'products.id', '=', 'order_items.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->selectRaw("COALESCE(categories.name, 'Tanpa Kategori') as name, SUM(order_items.quantity) as qty, SUM(order_items.price * order_items.quantity) as revenue")
            ->groupBy('categories.name')
            ->orderByDesc('revenue')
            ->get();

        $byColor = $items()
            ->leftJoin('product_sizes', 'product_sizes.id', '=', 'order_items.size_id')
            ->selectRaw("COALESCE(product_sizes.color, 'Standar') as name, SUM(order_items.quantity) as qty, SUM(order_items.price * order_items.quantity) as revenue")
            ->groupBy(DB::raw("COALESCE(product_sizes.color, 'Standar')"))
            ->orderByDesc('qty')
            ->get();

        return view('admin.orders.reports.index', [
            'from' => $from,
            'to' => $to,
            'period' => $period,
            'totalRevenue' => $totalRevenue,
            'orderCount' => $orderCount,
            'itemsSold' => $itemsSold,
            'avgOrder' => $avgOrder,
            'labels' => $labels,
            'values' => $values,
            'topProducts' => $topProducts,
            'byCategory' => $byCategory,
            'byColor' => $byColor,
        ]);
    }

    private function resolvePeriod(Request $request): array
    {
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to = Carbon::parse($request->to)->endOfDay();

            if ($from->gt($to)) {
                [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
            }

            return [$from, $to, 'custom'];
        }

        $period = $request->input('period', '30');

        if ($period === 'month') {
            return [now()->startOfMonth(), now()->endOfDay(), 'month'];
        }

        $days = in_array($period, ['7', '30', '90']) ? (int) $period : 30;

        return [now()->subDays($days - 1)->startOfDay(), now()->endOfDay(), (string) $days];
    }
}