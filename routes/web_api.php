<?php

use App\Http\Controllers\Rest\AppController;
use App\Http\Controllers\Rest\AppMenuController;
use App\Http\Controllers\Rest\AppProfileController;
use App\Http\Controllers\Rest\AppRoleController;
use App\Http\Controllers\Rest\AppUserController;
use App\Http\Controllers\Rest\AreaController;
use App\Http\Controllers\Rest\BillingTemplateController;
use App\Http\Controllers\Rest\BillingTypeController;
use App\Http\Controllers\Rest\CityController;
use App\Http\Controllers\Rest\CustomerController;
use App\Http\Controllers\Rest\CustomerSegmentController;
use App\Http\Controllers\Rest\CustomerTypeController;
use App\Http\Controllers\Rest\DepartementController;
use App\Http\Controllers\Rest\ProductPromoController;
use App\Http\Controllers\Rest\ProductServiceController;
use App\Http\Controllers\Rest\ProductTypeController;
use App\Http\Controllers\Rest\ProvinsiController;
use App\Http\Controllers\Rest\TaxController;
use Illuminate\Support\Facades\Route;

Route::prefix('app')->group(function() {
    Route::controller(AppController::class)->group(function() {
        Route::post('/menu', 'menu');
    });
});

Route::prefix('provinsi')->group(function() {
    Route::controller(ProvinsiController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');
    });
});

Route::prefix('city')->group(function() {
    Route::controller(CityController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');
    });
});

Route::prefix('departement')->group(function() {
    Route::controller(DepartementController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');        
    });
});

Route::prefix('tax')->group(function() {
    Route::controller(TaxController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');   
    });
});

Route::prefix('product-type')->group(function() {
    Route::controller(ProductTypeController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('product-service')->group(function() {
    Route::controller(ProductServiceController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');         
    });
});

Route::prefix('product-promo')->group(function() {
    Route::controller(ProductPromoController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');           
    });
});

Route::prefix('customer-type')->group(function() {
    Route::controller(CustomerTypeController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('customer-segment')->group(function() {
    Route::controller(CustomerSegmentController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('app-profile')->group(function() {
    Route::controller(AppProfileController::class)->group(function() {
        Route::get('/', 'index');
        
        Route::post('/', 'store');
    });
});

Route::prefix('app-role')->group(function() {
    Route::controller(AppRoleController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('app-menu')->group(function() {
    Route::controller(AppMenuController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('app-user')->group(function() {
    Route::controller(AppUserController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('billing-type')->group(function() {
    Route::controller(BillingTypeController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/product/{id}', 'productLists');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy'); 
        Route::delete('/product/{id}', 'productDestroy');
    });
});

Route::prefix('billing-template')->group(function() {
    Route::controller(BillingTemplateController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        Route::post('/preview', 'preview');
        
        Route::delete('/{id}', 'destroy'); 
    });
});

Route::prefix('area')->group(function() {
    Route::controller(AreaController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::get('/product/{id}', 'productLists');
        Route::get('/customer/{id}', 'customerLists');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');
        Route::delete('/product/{id}', 'productDestroy');
        Route::delete('/customer/{id}', 'customerDestroy');
    });
});

Route::prefix('customer')->group(function() {
    Route::controller(CustomerController::class)->group(function() {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');

        Route::post('/lists', 'lists');
        Route::post('/', 'store');
        
        Route::delete('/{id}', 'destroy');
    });
});