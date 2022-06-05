<?php

use App\Http\Controllers\Test\SampleController;
use Illuminate\Support\Facades\Route;

Route::prefix('sample')->group(function() {
    Route::controller(SampleController::class)->group(function() {
        Route::get('/', 'index');

        Route::get('/mikrotik', 'mikrotik');
    });
});