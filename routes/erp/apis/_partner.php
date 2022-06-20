<?php

use App\Http\Controllers\Rest\PartnerController;
use App\Http\Controllers\Rest\PartnerDataController;
use Illuminate\Support\Facades\Route;

Route::prefix('partner')->group(function() {
    Route::controller(PartnerController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/contact/{id}', 'contactLists');
        Route::get('/document/{id}', 'documentLists');
        Route::get('/document-show/{id}', 'documentShow');
        Route::get('/document-file/{file}', 'documentFile');

        Route::post('/', 'store');
        Route::post('/document', 'documentStore');
        
        Route::delete('/contact/{id}', 'contactDestroy');
        Route::delete('/document/{id}', 'documentDestroy');
    });
});

Route::prefix('partner-data')->group(function() {
    Route::controller(PartnerDataController::class)->group(function() {
        Route::post('/lists', 'lists');
        Route::post('/types', 'types');
        Route::post('/brands', 'brands');
    });
});