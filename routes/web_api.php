<?php

use App\Http\Controllers\Rest\AppController;
use App\Http\Controllers\Rest\AppMenuController;
use App\Http\Controllers\Rest\AppProfileController;
use App\Http\Controllers\Rest\AppRoleController;
use App\Http\Controllers\Rest\AppUserController;
use App\Http\Controllers\Rest\AreaController;
use App\Http\Controllers\Rest\AuthController;
use App\Http\Controllers\Rest\BankController;
use App\Http\Controllers\Rest\BillingInvoiceController;
use App\Http\Controllers\Rest\BillingTemplateController;
use App\Http\Controllers\Rest\BillingTypeController;
use App\Http\Controllers\Rest\CityController;
use App\Http\Controllers\Rest\CustomerController;
use App\Http\Controllers\Rest\CustomerSegmentController;
use App\Http\Controllers\Rest\CustomerTypeController;
use App\Http\Controllers\Rest\DepartementController;
use App\Http\Controllers\Rest\LogController;
use App\Http\Controllers\Rest\MikrotikController;
use App\Http\Controllers\Rest\ProductPromoController;
use App\Http\Controllers\Rest\ProductServiceController;
use App\Http\Controllers\Rest\ProductTypeController;
use App\Http\Controllers\Rest\ProvinsiController;
use App\Http\Controllers\Rest\ReportController;
use App\Http\Controllers\Rest\RouterSiteController;
use App\Http\Controllers\Rest\TaxController;
use App\Http\Controllers\Rest\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::prefix('mikrotik')->group(function() {
    Route::controller(MikrotikController::class)->group(function() {
        Route::get('/', 'index');

        Route::middleware('authMikrotik')->group(function() {
            Route::post('/users', 'users');
            Route::post('/ip-address', 'ipAddress');
        });
    });
});

Route::prefix('auth')->group(function() {
    Route::controller(AuthController::class)->group(function() {
        Route::post('/login', 'login');
        Route::post('/forgot', 'forgot');
    });
});

Route::middleware('authApp')->group(function() {

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

    Route::prefix('bank')->group(function() {
        Route::controller(BankController::class)->group(function() {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
    
            Route::post('/lists', 'lists');
            Route::post('/', 'store');
            
            Route::delete('/{id}', 'destroy');  
        });
    });

    Route::prefix('router-site')->group(function() {
        Route::controller(RouterSiteController::class)->group(function() {
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
            Route::get('/area/{id}', 'areaLists');
    
            Route::post('/area-filter', 'areaFilter');
            Route::post('/lists', 'lists');
            Route::post('/', 'store');
            
            Route::delete('/{id}', 'destroy');
            Route::delete('/area/{id}', 'areaDestroy');
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
            Route::post('/reset-secret', 'reset_secret');
        });
    });
    
    Route::prefix('app-role')->group(function() {
        Route::controller(AppRoleController::class)->group(function() {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::get('/departement/{id}', 'departementLists');
    
            Route::post('/menu/{id}', 'menuLists');
            Route::post('/lists', 'lists');
            Route::post('/', 'store');
            
            Route::delete('/{id}', 'destroy'); 
            Route::delete('/departement/{id}', 'departementDestroy');
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
            Route::get('/router-site/{id}', 'routersiteLists');
            Route::get('/update-price/{id}', 'updatePriceList');
    
            Route::post('/lists', 'lists');
            Route::post('/', 'store');
            Route::post('/update-price', 'updatePriceStore');
            
            Route::delete('/{id}', 'destroy');
            Route::delete('/product/{id}', 'productDestroy');
            Route::delete('/customer/{id}', 'customerDestroy');
            Route::delete('/router-site/{id}', 'routersiteDestroy');
            Route::delete('/update-price/{id}', 'updatePriceDestroy');
        });
    });
    
    Route::prefix('customer')->group(function() {
        Route::controller(CustomerController::class)->group(function() {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::get('/contact/{id}', 'contactLists');
            Route::get('/document/{id}', 'documentLists');
            Route::get('/document-show/{id}', 'documentShow');
            Route::get('/document-file/{file}', 'documentFile');
    
            Route::post('/lists', 'lists');
            Route::post('/contact-merge/{id}', 'contactMerge');
            Route::post('/', 'store');
            Route::post('/document', 'documentStore');
            
            Route::delete('/contact/{id}', 'contactDestroy');
            Route::delete('/document/{id}', 'documentDestroy');
        });
    });
    
    Route::prefix('billing-invoice')->group(function() {
        Route::controller(BillingInvoiceController::class)->group(function() {
            Route::get('/', 'index');
            Route::get('/{id}', 'show');
            Route::get('/viewfile/{id}/{type}/{filename}', 'viewfile');
    
            Route::post('/', 'verif');
            Route::post('/unsuspend/{id}', 'unsuspend');
            Route::post('/resend/{id}', 'resend');
        });
    });
    
    Route::prefix('profile')->group(function() {
        Route::controller(UserProfileController::class)->group(function() {
            Route::get('/', 'index');
            
            Route::post('/', 'store');
            Route::post('/logout', 'logout');
        });
    });

    Route::prefix('app-log')->group(function() {
        Route::controller(LogController::class)->group(function() {
            Route::get('/', 'index');
            Route::get('/{filename}', 'show');
            Route::get('/download/{filename}', 'download');
        });
    });

    Route::prefix('report')->group(function() {
        Route::controller(ReportController::class)->group(function() {
            Route::post('/totals', 'totals');
            Route::post('/area', 'areaChart');
            Route::post('/product-service', 'productServiceChart');
            Route::post('/customer-segment', 'customerSegmentChart');
            Route::post('/list-invoice-pay', 'listInvoicePay');
        });
    });

});