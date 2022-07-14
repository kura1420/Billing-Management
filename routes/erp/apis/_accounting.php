<?php

use App\Http\Controllers\Rest\BankController;
use App\Http\Controllers\Rest\TaxController;
use Illuminate\Support\Facades\Route;

Route::prefix('tax')->group(function() {
    Route::controller(TaxController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');   
    });
});

Route::prefix('bank')->group(function() {
    Route::controller(BankController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');  
    });
});