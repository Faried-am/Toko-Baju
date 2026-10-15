<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.product'])->latest()->get();

        return view('admin.orders.index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.size']);

        return view('admin.orders.show', [
            'order' => $order
        ]);
    }

    public function updateShipping(Request $request, Order $order)
    {
        if ($order->status !== 'paid') {
            return back()->with('error', 'Status pengiriman hanya bisa diubah untuk pesanan yang sudah dibayar.');
        }

        $data = $request->validate([
            'shipping_status' => 'required|in:pending,processing,shipped,completed',
            'courier' => 'nullable|string|max:50',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        if (in_array($data['shipping_status'], ['shipped', 'completed'])
            && (empty($data['courier']) || empty($data['tracking_number']))) {
            return back()
                ->withInput()
                ->withErrors(['tracking_number' => 'Kurir dan nomor resi wajib diisi untuk status Dikirim atau Selesai.']);
        }

        $update = [
            'shipping_status' => $data['shipping_status'],
            'courier' => $data['courier'] ?? null,
            'tracking_number' => $data['tracking_number'] ?? null,
        ];

        if ($data['shipping_status'] === 'shipped' && !$order->shipped_at) {
            $update['shipped_at'] = now();
        }

        if ($data['shipping_status'] === 'completed') {
            $update['shipped_at'] = $order->shipped_at ?? now();
            $update['completed_at'] = $order->completed_at ?? now();
        }

        if (in_array($data['shipping_status'], ['pending', 'processing'])) {
            $update['shipped_at'] = null;
            $update['completed_at'] = null;
        }

        $order->update($update);

        return back()->with('success', 'Status pengiriman diperbarui.');
    }
}