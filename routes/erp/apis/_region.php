<?php

use App\Http\Controllers\Rest\CityController;
use App\Http\Controllers\Rest\ProvinsiController;
use Illuminate\Support\Facades\Route;

Route::prefix('provinsi')->group(function() {
    Route::controller(ProvinsiController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');
    });
});

Route::prefix('city')->group(function() {
    Route::controller(CityController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');
    });
});