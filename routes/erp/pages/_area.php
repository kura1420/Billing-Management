<?php

use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;

Route::prefix('area')->group(function() {
    Route::controller(AreaController::class)->group(function() {
        Route::get('/', 'index');
    });
});