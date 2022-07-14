<?php

use App\Http\Controllers\Rest\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('report')->group(function() {
    Route::controller(ReportController::class)->group(function() {
        Route::post('/totals', 'totals');
        Route::post('/area', 'areaChart');
        Route::post('/product-service', 'productServiceChart');
        Route::post('/customer-segment', 'customerSegmentChart');
        Route::post('/list-invoice-pay', 'listInvoicePay');
    });
});