<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')->latest()->get();

        return view('sales.index', [
            'sales' => $sales
        ]);
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();

        return view('sales.create', [
            'products' => $products
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->stock) {
            return back()->withErrors(['quantity' => 'Stok tidak mencukupi. Stok tersisa: ' . $product->stock]);
        }

        $totalPrice = $product->price * $request->quantity;

        Sale::create([
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
        ]);

        $product->decrement('stock', $request->quantity);

        return redirect()->route('sales.index')->with('success', 'Penjualan berhasil dicatat!');
    }

    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Data penjualan berhasil dihapus!');
    }
}