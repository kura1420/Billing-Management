<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::prefix('employee')->group(function() {
    Route::controller(EmployeeController::class)->group(function() {
        Route::get('/profile', 'profile');
    });
});