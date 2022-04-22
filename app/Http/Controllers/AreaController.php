<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AreaController extends Controller
{
    //
    const FOLDER = 'area.';

    public function index()
    {
        return view(self::FOLDER . 'data.main');
    }

    public function product()
    {
        # code...
    }

    public function product_customer()
    {
        # code...
    }
}
