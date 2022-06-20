<?php

use App\Http\Controllers\RegionController;
use Illuminate\Support\Facades\Route;

Route::prefix('region')->group(function() {
    Route::controller(RegionController::class)->group(function() {
        Route::get('/provinsi', 'provinsi');
        Route::get('/city', 'city');
    });
});