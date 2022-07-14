<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountingController extends Controller
{
    //
    const FOLDER = 'pages.accounting.';

    public function tax()
    {
        return view(self::FOLDER . 'tax.main');
    }

    public function bank()
    {
        return view(self::FOLDER . 'bank.main');
    }
}
