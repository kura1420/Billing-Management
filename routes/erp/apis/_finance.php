<?php

use App\Http\Controllers\Rest\BillingInvoiceController;
use App\Http\Controllers\Rest\BillingTemplateController;
use App\Http\Controllers\Rest\BillingTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('billing-type')->group(function() {
    Route::controller(BillingTypeController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/product/{id}', 'productLists');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
        Route::delete('/product/{id}', 'productDestroy');
    });
});

Route::prefix('billing-template')->group(function() {
    Route::controller(BillingTemplateController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        Route::post('/preview', 'preview');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('billing-invoice')->group(function() {
    Route::controller(BillingInvoiceController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/viewfile/{id}/{type}/{filename}', 'viewfile');

        Route::post('/', 'verif');
        Route::post('/unsuspend/{id}', 'unsuspend');
        Route::post('/resend/{id}', 'resend');
    });
});