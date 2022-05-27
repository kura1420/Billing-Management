<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigController extends Controller
{
    //
    const FOLDER = 'config.';

    public function tax()
    {
        return view(self::FOLDER . 'tax.main');
    }

    public function bank()
    {
        return view(self::FOLDER . 'bank.main');
    }
}
