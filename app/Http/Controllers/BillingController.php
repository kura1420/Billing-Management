<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    //
    const FOLDER = 'billing.';
    
    public function type()
    {
        return view(self::FOLDER . 'type.main');
    }
    
    public function setting()
    {
        # code...
    }

    public function template()
    {
        return view(self::FOLDER . 'template.main');
    }

    public function invoice()
    {
        # code...
    }
}
