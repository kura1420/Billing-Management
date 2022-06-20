<?php

use App\Http\Controllers\Rest\UnitController;
use Illuminate\Support\Facades\Route;

Route::prefix('unit')->group(function() {
    Route::controller(UnitController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');             
    });
});