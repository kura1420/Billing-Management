<?php

use App\Http\Controllers\InformationTechnologyController;
use Illuminate\Support\Facades\Route;

Route::prefix('information-technology')->group(function() {
    Route::controller(InformationTechnologyController::class)->group(function() {
        Route::get('/router-site', 'router_site');
        Route::get('/radius-api', 'radius_api');
    });
});