<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/products', function () {
    return \App\Models\Product::where('is_active', true)
        ->where('stock_quantity', '>', 0)
        ->get();
});




