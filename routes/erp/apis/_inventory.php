<?php

use App\Http\Controllers\Rest\ItemController;
use App\Http\Controllers\Rest\ItemDataController;
use App\Http\Controllers\Rest\UnitController;
use Illuminate\Support\Facades\Route;

Route::prefix('unit')->group(function() {
    Route::controller(UnitController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/', 'store');
        Route::post('/lists', 'lists');
        
        Route::delete('/{id}', 'destroy');       
    });
});

Route::prefix('item')->group(function() {
    Route::controller(ItemController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');
    });
});

Route::prefix('item-data')->group(function() {
    Route::controller(ItemDataController::class)->group(function() {
        Route::get('/lists', 'lists');
        Route::post('/brands', 'brands') ;
    });
});