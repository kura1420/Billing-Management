<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function() {
    Route::controller(CustomerController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/type', 'type');
        Route::get('/segment', 'segment');
        Route::get('/candidate', 'candidate');
    });
});