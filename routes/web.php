<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CoreController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\UserController;
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
        Route::get('/login', 'login');
        Route::get('/forgot', 'forgot');
        Route::get('/reset', 'reset');
    });
});

Route::prefix('area')->group(function() {
    Route::controller(AreaController::class)->group(function() {
        Route::get('/', 'index');
    });
});

Route::prefix('billing')->group(function() {
    Route::controller(BillingController::class)->group(function() {
        Route::get('/type', 'type');
        Route::get('/template', 'template');
        Route::get('/invoice', 'invoice');
    });
});

Route::prefix('config')->group(function() {
    Route::controller(ConfigController::class)->group(function() {
        Route::get('/tax', 'tax');
    });
});

Route::prefix('core')->group(function() {
    Route::controller(CoreController::class)->group(function() {
        Route::get('/profile', 'profile');
        Route::get('/user', 'user');
        Route::get('/role', 'role');
        Route::get('/menu', 'menu');
    });
});

Route::prefix('customer')->group(function() {
    Route::controller(CustomerController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/type', 'type');
        Route::get('/segment', 'segment');
    });
});

Route::prefix('organization')->group(function() {
    Route::controller(OrganizationController::class)->group(function() {
        Route::get('/departement', 'departement');
    });
});

Route::prefix('product')->group(function() {
    Route::controller(ProductController::class)->group(function() {
        Route::get('/service', 'service');
        Route::get('/type', 'type');
        Route::get('/promo', 'promo');
    });
});

Route::prefix('region')->group(function() {
    Route::controller(RegionController::class)->group(function() {
        Route::get('/provinsi', 'provinsi');
        Route::get('/city', 'city');
    });
});

Route::prefix('user')->group(function() {
    Route::controller(UserController::class)->group(function() {
        Route::get('/profile', 'profile');
    });
});