<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FinanceController extends Controller
{
    //
    const FOLDER = 'pages.finance.';
    
    public function billing_type()
    {
        return view(self::FOLDER . 'billing.type.main');
    }

    public function billing_template()
    {
        return view(self::FOLDER . 'billing.template.main');
    }

    public function billing_invoice()
    {
        return view(self::FOLDER . 'billing.invoice.main');
    }
}
