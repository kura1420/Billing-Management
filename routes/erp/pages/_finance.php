<?php

use App\Http\Controllers\FinanceController;
use Illuminate\Support\Facades\Route;

Route::prefix('finance')->group(function() {
    Route::controller(FinanceController::class)->group(function() {
        Route::get('/billing-type', 'billing_type');
        Route::get('/billing-template', 'billing_template');
        Route::get('/billing-invoice', 'billing_invoice');
    });
});