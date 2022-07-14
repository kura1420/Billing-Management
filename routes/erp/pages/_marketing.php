<?php

use App\Http\Controllers\MarketingController;
use Illuminate\Support\Facades\Route;

Route::prefix('marketing')->group(function() {
    Route::controller(MarketingController::class)->group(function() {
        Route::get('/product-type', 'product_type');
        Route::get('/product-service', 'product_service');
        Route::get('/product-promo', 'product_promo');
    });
});