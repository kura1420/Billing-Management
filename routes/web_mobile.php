<?php

use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\PageController;
use App\Http\Controllers\Mobile\RestController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::get('/', 'loginPage');

        Route::post('/login', 'loginCheck');
    });
});

Route::middleware('authMobile')->group(function() {
    Route::prefix('pages')->group(function() {
        Route::controller(PageController::class)->group(function() {
            Route::get('/', 'index');
        });
    });
    
    Route::prefix('rest')->group(function() {
        Route::controller(RestController::class)->group(function() {
            Route::get('/profile', 'profile');
            
            Route::post('/provinsi', 'provinsi');
            Route::post('/city', 'city');
            Route::post('/profile', 'updateProfile');
            Route::post('/segments', 'segments');
            Route::post('/products', 'products');
            Route::post('/customer-candidate', 'customerCandidate');
            Route::post('/logout', 'logout');
        });
    });
});
