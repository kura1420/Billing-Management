<?php

use App\Http\Controllers\Rest\AreaController;
use Illuminate\Support\Facades\Route;

Route::prefix('area')->group(function() {
    Route::controller(AreaController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/product/{id}', 'productLists');
        Route::get('/customer/{id}', 'customerLists');
        Route::get('/router-site/{id}', 'routersiteLists');
        Route::get('/update-price/{id}', 'updatePriceList');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        Route::post('/update-price', 'updatePriceStore');
        
        Route::delete('/{id}', 'destroy');
        Route::delete('/product/{id}', 'productDestroy');
        Route::delete('/customer/{id}', 'customerDestroy');
        Route::delete('/router-site/{id}', 'routersiteDestroy');
        Route::delete('/update-price/{id}', 'updatePriceDestroy');
    });
});