<?php

use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RegionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['authApi'])->group(function() {
    Route::prefix('region')->group(function() {
        Route::controller(RegionController::class)->group(function() {
            Route::post('/provinsi', 'provinsi');
            Route::post('/city', 'city');
        });
    });

    Route::prefix('area')->group(function() {
        Route::controller(AreaController::class)->group(function() {
            Route::post('/', 'index');
        });
    });
    
    Route::prefix('product')->group(function() {
        Route::controller(ProductController::class)->group(function() {
            Route::post('/type', 'type');
            Route::post('/service', 'service');
            Route::post('/promos', 'promos');
        });
    });
    
    Route::prefix('customer')->group(function() {
        Route::controller(CustomerController::class)->group(function() {
            Route::post('/type', 'type');
            Route::post('/segment', 'segment');
            Route::post('/profile/{code}', 'show');
        });
    });
});