<?php

use App\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory')->group(function() {
    Route::controller(InventoryController::class)->group(function() {
        Route::get('/unit', 'unit');
    });
});