<?php

use App\Http\Controllers\Rest\AppMenuController;
use App\Http\Controllers\Rest\AppProfileController;
use App\Http\Controllers\Rest\AppRoleController;
use App\Http\Controllers\Rest\AppUserController;
use App\Http\Controllers\Rest\LogController;
use Illuminate\Support\Facades\Route;

Route::prefix('app-profile')->group(function() {
    Route::controller(AppProfileController::class)->group(function() {
        Route::get('/', 'index');
        
        Route::post('/', 'store');
        Route::post('/reset-secret', 'reset_secret');
    });
});

Route::prefix('app-user')->group(function() {
    Route::controller(AppUserController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/', 'store');
        Route::post('/lists', 'lists');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('app-role')->group(function() {
    Route::controller(AppRoleController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/departement/{id}', 'departementLists');

        Route::post('/menu/{id}', 'menuLists');
        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
        Route::delete('/departement/{id}', 'departementDestroy');
    });
});

Route::prefix('app-menu')->group(function() {
    Route::controller(AppMenuController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('app-log')->group(function() {
    Route::controller(LogController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{filename}', 'show');
        Route::get('/download/{filename}', 'download');
    });
});