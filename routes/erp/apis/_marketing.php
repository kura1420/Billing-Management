<?php

use App\Http\Controllers\Rest\ProductPromoController;
use App\Http\Controllers\Rest\ProductServiceController;
use App\Http\Controllers\Rest\ProductTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('product-type')->group(function() {
    Route::controller(ProductTypeController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('product-service')->group(function() {
    Route::controller(ProductServiceController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');         
    });
});

Route::prefix('product-promo')->group(function() {
    Route::controller(ProductPromoController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/area/{id}', 'areaLists');

        Route::post('/area-filter', 'areaFilter');
        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');
        Route::delete('/area/{id}', 'areaDestroy');
    });
});