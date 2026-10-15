<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class CartServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view) {
            $cart = session('cart', []);
            $cartCount = collect($cart)->sum('quantity');

            $view->with('cartCount', $cartCount);
        });
    }
}