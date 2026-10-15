<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductReview;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('success', 'Keranjang masih kosong.');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Isi otomatis dari alamat pesanan terakhir
        $lastOrder = Order::where('user_id', auth()->id())
            ->whereNotNull('address')
            ->latest()
            ->first();

        return view('checkout.index', [
            'cart' => $cart,
            'total' => $total,
            'lastOrder' => $lastOrder,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'recipient_name' => 'required|string|max:100',
            'phone' => ['required', 'regex:/^[0-9+\-\s]{9,20}$/'],
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|digits_between:4,10',
            'note' => 'nullable|string|max:255',
        ], [
            'phone.regex' => 'Nomor HP tidak valid.',
            'postal_code.digits_between' => 'Kode pos harus berupa angka.',
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

        // Validasi ulang stok sebelum membuat order
        foreach ($cart as $item) {
            $productSize = ProductSize::find($item['size_id']);

            if (!$productSize) {
                return redirect()->route('cart.index')
                    ->with('success', "Produk \"{$item['name']}\" (Size {$item['size']}) sudah tidak tersedia.");
            }

            if ($productSize->stock < $item['quantity']) {
                return redirect()->route('cart.index')
                    ->with('success', "Stok \"{$item['name']}\" (Size {$item['size']}) tersisa {$productSize->stock}, tidak cukup untuk pesanan kamu.");
            }
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'total_price' => $total,
            'status' => 'pending',
            'recipient_name' => $data['recipient_name'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'city' => $data['city'],
            'postal_code' => $data['postal_code'],
            'note' => $data['note'] ?? null,
        ]);

        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'size_id' => $item['size_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Setup Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->recipient_name,
                'email' => auth()->user()->email,
                'phone' => $order->phone,
                'shipping_address' => [
                    'first_name' => $order->recipient_name,
                    'phone' => $order->phone,
                    'address' => $order->address,
                    'city' => $order->city,
                    'postal_code' => $order->postal_code,
                ],
            ],
            'callbacks' => [
                'finish' => route('checkout.finish'),
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $paymentUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}";

        $order->update(['payment_url' => $paymentUrl]);

        session()->forget('cart');

        return redirect($paymentUrl);
    }

    public function finish(Request $request)
    {
        $order = Order::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$order) {
            return redirect()->route('shop.index');
        }

        return redirect()->route('checkout.success', $order->id);
    }

    public function success(Order $order)
    {
    abort_if($order->user_id !== auth()->id(), 403);

    return view('checkout.success', [
        'order' => $order
    ]);
        }

    public function history()
    {
        $orders = Order::with('items.product', 'items.size')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $myReviews = ProductReview::where('user_id', auth()->id())
            ->pluck('rating', 'product_id');

        return view('checkout.history', [
            'orders' => $orders,
            'myReviews' => $myReviews,
        ]);
    }
    public function received(Order $order)
{
    abort_if($order->user_id !== auth()->id(), 403);

    if ($order->status !== 'paid' || $order->shipping_status !== 'shipped') {
        return back()->with('error', 'Pesanan ini belum bisa dikonfirmasi diterima.');
    }

    $order->update([
        'shipping_status' => 'completed',
        'completed_at' => now(),
    ]);

    return back()->with('success', 'Terima kasih, pesanan ditandai sudah diterima.');
}
}