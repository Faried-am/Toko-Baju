<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('cart.index', [
            'cart' => $cart,
            'total' => $total
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $request->validate([
            'size_id' => 'required|exists:product_sizes,id',
        ]);

        $productSize = ProductSize::findOrFail($request->size_id);

        if ($productSize->stock <= 0) {
            return back()->with('success', 'Maaf, size tersebut sudah habis.');
        }

        $cart = session('cart', []);
        $cartKey = $product->id . '-' . $productSize->id;

        if (isset($cart[$cartKey])) {
            if ($cart[$cartKey]['quantity'] < $productSize->stock) {
                $cart[$cartKey]['quantity']++;
            }
} else {
    $colorImage = null;
    if ($productSize->color) {
        $colorImage = $product->images()
            ->get()
            ->first(fn ($img) => strtolower(trim($img->color ?? '')) === strtolower(trim($productSize->color)))
            ?->image;
    }

    $cart[$cartKey] = [
        'product_id' => $product->id,
        'size_id' => $productSize->id,
        'name' => $product->name,
        'color' => $productSize->color,
        'size' => $productSize->size,
        'price' => $product->price,
        'image' => $colorImage ?? $product->image,
        'quantity' => 1,
    ];
}
        session(['cart' => $cart]);

        $qty = $cart[$cartKey]['quantity'];

        return back()->with('success', "{$product->name} (Size {$productSize->size}) ditambahkan ({$qty}x di keranjang)");
    }

    public function remove($cartKey)
    {
        $cart = session('cart', []);
        unset($cart[$cartKey]);
        session(['cart' => $cart]);

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }
}