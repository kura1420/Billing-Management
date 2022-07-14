<?php

use App\Http\Controllers\PartnerController;
use Illuminate\Support\Facades\Route;

Route::prefix('partner')->group(function() {
    Route::controller(PartnerController::class)->group(function() {
        Route::get('/', 'index');
    });
});