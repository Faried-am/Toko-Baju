<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount(['products' => function ($query) {
            $query->whereHas('sizes', function ($q) {
                $q->where('stock', '>', 0);
            });
        }])->get();

        $query = Product::with('category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->whereHas('sizes', function ($q) {
                $q->where('stock', '>', 0);
            });

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->latest()->get();

        if ($request->ajax()) {
            return view('shop.partials.product-grid', [
                'products' => $products,
                'searchTerm' => $request->search
            ]);
        }

        return view('shop.index', [
            'categories' => $categories,
            'products' => $products,
            'selectedCategory' => $request->category,
            'searchTerm' => $request->search,
            'minPrice' => $request->min_price,
            'maxPrice' => $request->max_price,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'sizes', 'images', 'reviews.user']);

        $canReview = Order::where('user_id', auth()->id())
         ->where('status', 'paid')
        ->where('shipping_status', 'completed')
        ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
        ->exists();

        $myReview = $product->reviews->firstWhere('user_id', auth()->id());

        return view('shop.show', [
            'product' => $product,
            'canReview' => $canReview,
            'myReview' => $myReview,
        ]);
    }
}