<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'name', 'size', 'description', 'price', 'stock', 'image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->latest();
    }
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
}