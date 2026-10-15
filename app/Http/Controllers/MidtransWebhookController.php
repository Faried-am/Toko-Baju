<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductSize;
use App\Models\Sale;
use Illuminate\Http\Request;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;

        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signature !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $order = Order::with('items.product')->where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $transactionStatus = $request->transaction_status;

        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {

            if ($order->status !== 'paid') {
                $order->update(['status' => 'paid', 'shipping_status' => 'processing']);

                $alreadyRecorded = Sale::where('order_id', $order->id)->exists();

                if (!$alreadyRecorded) {
                    foreach ($order->items as $item) {
                        Sale::create([
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                            'total_price' => $item->price * $item->quantity,
                        ]);

                        if ($item->size_id) {
                            $productSize = ProductSize::find($item->size_id);
                            if ($productSize) {
                                $productSize->decrement('stock', $item->quantity);
                            }
                        }
                    }
                }
            }

        } elseif ($transactionStatus === 'expire') {
            $order->update(['status' => 'expired']);
        } elseif ($transactionStatus === 'cancel' || $transactionStatus === 'deny') {
            $order->update(['status' => 'cancelled']);
        }

        return response()->json(['message' => 'OK']);
    }
}