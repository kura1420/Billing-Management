<?php

use App\Http\Controllers\Rest\DepartementController;
use Illuminate\Support\Facades\Route;

Route::prefix('departement')->group(function() {
    Route::controller(DepartementController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');        
    });
});