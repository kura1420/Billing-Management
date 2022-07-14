<?php

use App\Http\Controllers\Rest\RadiusApiController;
use App\Http\Controllers\Rest\RouterSiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('router-site')->group(function() {
    Route::controller(RouterSiteController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        Route::post('/test-connection', 'testConnection');
        Route::post('/test-command-list', 'testCommandList');
        Route::post('/test-command-comment', 'testCommandComment');
        Route::post('/test-command-disable', 'testCommandDisable');
        
        Route::delete('/{id}', 'destroy');  
    });
});

Route::prefix('radius-api')->group(function() {
    Route::controller(RadiusApiController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/', 'store');
        Route::post('/test-connection', 'testConnection');
        
        Route::delete('/{id}', 'destroy'); 
    });
});