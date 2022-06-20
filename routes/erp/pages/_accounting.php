<?php

use App\Http\Controllers\AccountingController;
use Illuminate\Support\Facades\Route;

Route::prefix('accounting')->group(function() {
    Route::controller(AccountingController::class)->group(function() {
        Route::get('/tax', 'tax');
        Route::get('/bank', 'bank');
    });
});