<?php

use App\Http\Controllers\Rest\CustomerCandidateController;
use App\Http\Controllers\Rest\CustomerController;
use App\Http\Controllers\Rest\CustomerSegmentController;
use App\Http\Controllers\Rest\CustomerTypeController;
use Illuminate\Support\Facades\Route;
    
Route::prefix('customer-type')->group(function() {
    Route::controller(CustomerTypeController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('customer-segment')->group(function() {
    Route::controller(CustomerSegmentController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('customer')->group(function() {
    Route::controller(CustomerController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/contact/{id}', 'contactLists');
        Route::get('/document/{id}', 'documentLists');
        Route::get('/document-show/{id}', 'documentShow');
        Route::get('/document-file/{file}', 'documentFile');
        Route::get('/device/{id}', 'deviceLists');
        Route::get('/device-show/{id}', 'deviceShow');
        Route::get('/device-file/{id}', 'deviceFile');

        Route::post('/lists', 'lists');
        Route::post('/contact-merge/{id}', 'contactMerge');
        Route::post('/', 'store');
        Route::post('/document', 'documentStore');
        Route::post('/device', 'deviceStore');
        
        Route::delete('/contact/{id}', 'contactDestroy');
        Route::delete('/document/{id}', 'documentDestroy');
        Route::delete('/device/{id}', 'deviceDelete');
    });
});

Route::prefix('customer-candidate')->group(function() {
    Route::controller(CustomerCandidateController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/view-file/{id}/{field}', 'viewFile');

        Route::post('/lists', 'lists');
        Route::post('/create', 'store');
        Route::post('/update', 'update');
        
        Route::delete('/{id}', 'destroy'); 
    });
});