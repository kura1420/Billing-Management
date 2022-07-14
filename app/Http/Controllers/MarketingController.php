<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketingController extends Controller
{
    //
    const FOLDER = 'pages.marketing.';
    
    public function product_type()
    {
        return view(self::FOLDER . 'product.type.main');
    }

    public function product_service()
    {
        return view(self::FOLDER . 'product.service.main');
    }
    
    public function product_promo()
    {
        return view(self::FOLDER . 'product.promo.main');
    }
}
