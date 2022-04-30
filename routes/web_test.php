<?php

use App\Http\Controllers\Test\BillingController;
use Illuminate\Support\Facades\Route;

Route::prefix('billing')->group(function() {
    Route::controller(BillingController::class)->group(function () {
        Route::get('/invoice', 'invoice');
    });
});