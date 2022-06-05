<?php

use App\Http\Controllers\Mikrotik\HotspotController;
use Illuminate\Support\Facades\Route;

Route::prefix('hotspot')->group(function() {
    Route::controller(HotspotController::class)->group(function() {
        Route::get('/users', 'userList');
        Route::post('/users', 'userStore');
        Route::post('/users-generate', 'userGenerate');
        
        Route::get('/profiles', 'profileList');

        Route::post('/active', 'active');
    });
});