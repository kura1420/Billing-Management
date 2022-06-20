<?php

use App\Http\Controllers\Rest\AppController;
use App\Http\Controllers\Rest\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('/login', 'login');
        Route::post('/forgot', 'forgot');
    });
});

Route::middleware('authApp')->group(function() {

    require_once 'erp/apis/_region.php';
    require_once 'erp/apis/_organization.php';
    require_once 'erp/apis/_accounting.php';
    require_once 'erp/apis/_information_technology.php';
    require_once 'erp/apis/_marketing.php';    
    require_once 'erp/apis/_finance.php';
    require_once 'erp/apis/_area.php';
    require_once 'erp/apis/_customer.php';
    require_once 'erp/apis/_employee.php';
    require_once 'erp/apis/_core.php';
    require_once 'erp/apis/_inventory.php';
    require_once 'erp/apis/_partner.php';

    Route::prefix('app')->group(function() {
        Route::controller(AppController::class)->group(function() {
            Route::post('/menu', 'menu');
        });
    });

});