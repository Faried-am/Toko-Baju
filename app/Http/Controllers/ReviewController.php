<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $products = Product::with('reviews.user')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->has('reviews')
            ->get()
            ->sortByDesc('reviews_avg_rating');

        return view('admin.reviews.index', ['products' => $products]);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

$hasBought = Order::where('user_id', auth()->id())
    ->where('status', 'paid')
    ->where('shipping_status', 'completed')
    ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
    ->exists();
    
        if (!$hasBought) {
           return back()->with('error', 'Rating hanya bisa diberikan setelah pesanan diterima.');
        }

        ProductReview::updateOrCreate(
            ['product_id' => $product->id, 'user_id' => auth()->id()],
            ['rating' => $request->rating]
        );

        return back()->with('success', 'Terima kasih, rating kamu tersimpan!');
    }

    public function destroy(ProductReview $review)
    {
        if ($review->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Rating dihapus.');
    }
}