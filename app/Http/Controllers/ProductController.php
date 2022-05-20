<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    const FOLDER = 'product.';
    
    public function type()
    {
        return view(self::FOLDER . 'type.main');
    }

    public function service()
    {
        return view(self::FOLDER . 'service.main');
    }
    
    public function promo()
    {
        return view(self::FOLDER . 'promo.main');
    }
}
