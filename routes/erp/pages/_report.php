<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('report')->group(function() {
    Route::controller(ReportController::class)->group(function() {
        Route::get('/sample-summary', 'sample_summary');
    });
});