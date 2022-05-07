<?php

use App\Http\Controllers\Test\AuthController;
use App\Http\Controllers\Test\BillingController;
use Illuminate\Support\Facades\Route;

Route::prefix('billing')->group(function() {
    Route::controller(BillingController::class)->group(function () {
        Route::get('/invoice', 'invoice');
        Route::get('/suspend', 'suspend');
    });
});

Route::prefix('auth')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::get('/forgot', 'forgot');
        Route::get('/reset', 'reset');
    });
});