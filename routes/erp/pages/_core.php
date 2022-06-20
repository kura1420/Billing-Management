<?php

use App\Http\Controllers\CoreController;
use Illuminate\Support\Facades\Route;

Route::prefix('core')->group(function() {
    Route::controller(CoreController::class)->group(function() {
        Route::get('/profile', 'profile');
        Route::get('/user', 'user');
        Route::get('/role', 'role');
        Route::get('/menu', 'menu');
        Route::get('/log', 'log');
    });
});