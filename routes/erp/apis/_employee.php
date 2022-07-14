<?php

use App\Http\Controllers\Rest\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('profile')->group(function() {
    Route::controller(UserProfileController::class)->group(function() {
        Route::get('/', 'index');
        
        Route::post('/', 'store');
        Route::post('/logout', 'logout');
    });
});