<?php

use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle']);

Route::get('/', function () {
    return redirect()->route('login');
});


Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return view('dashboard', [
            'totalProducts' => \App\Models\Product::count(),
            'totalCategories' => \App\Models\Category::count(),
            'totalStock' => \App\Models\ProductSize::sum('stock'),
            'lowStockVariants' => \App\Models\ProductSize::with('product')
                ->where('stock', '<=', 5)
                ->orderBy('stock')
                ->get(),
            'totalRevenue' => \App\Models\Sale::sum('total_price'),
            'recentSales' => \App\Models\Sale::with('product')->latest()->take(5)->get(),
        ]);
    }

    return redirect()->route('shop.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');

    Route::post('/shop/{product}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{cartKey}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/{order}/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/finish', [CheckoutController::class, 'finish'])->name('checkout.finish');
    Route::get('/orders', [CheckoutController::class, 'history'])->name('orders.history');

    Route::put('/orders/{order}/received', [CheckoutController::class, 'received'])->name('orders.received');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');

    Route::get('/admin/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index'); 
    Route::get('/admin/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show'); 

    Route::put('/admin/orders/{order}/shipping', [\App\Http\Controllers\Admin\OrderController::class, 'updateShipping'])->name('admin.orders.shipping');

    Route::get('/admin/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('admin.reports.index');
    
    Route::delete('/products/images/{image}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');

    Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [\App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
Route::get('/categories/{category}/edit', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
Route::put('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

Route::get('/admin/reviews', [\App\Http\Controllers\ReviewController::class, 'index'])->name('admin.reviews.index');
});

require __DIR__.'/auth.php';