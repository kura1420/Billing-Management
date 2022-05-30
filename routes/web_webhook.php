<?php

use App\Http\Controllers\Webhook\OYIndonesiaController;
use Illuminate\Support\Facades\Route;

Route::controller(OYIndonesiaController::class)->group(function() {
    Route::get('/fund-acceptance/payment-link/{id}/{code}/create', 'fundAcceptancePaymentLinkCreate');

    Route::post('/fund-acceptance/callback', 'fundAcceptanceCallback');
    Route::post('/static-va/callback', 'staticVACallback');
});