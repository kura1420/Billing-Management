<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('layouts.base');
});

Route::prefix('auth')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::get('/reset/{token}', 'reset');
    });
});

Route::middleware('authApp')->group(function() {

    require_once 'erp/pages/_accounting.php';
    require_once 'erp/pages/_area.php';
    require_once 'erp/pages/_core.php';
    require_once 'erp/pages/_customer.php';
    require_once 'erp/pages/_employee.php';
    require_once 'erp/pages/_finance.php';
    require_once 'erp/pages/_information_technology.php';
    require_once 'erp/pages/_inventory.php';
    require_once 'erp/pages/_marketing.php';
    require_once 'erp/pages/_operation.php';
    require_once 'erp/pages/_organization.php';
    require_once 'erp/pages/_partner.php';
    require_once 'erp/pages/_region.php';
    require_once 'erp/pages/_report.php';
    
});