<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::prefix('organization')->group(function() {
    Route::controller(OrganizationController::class)->group(function() {
        Route::get('/departement', 'departement');
    });
});