<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    const FOLDER = 'customer.';

    public function index()
    {
        # code...
    }

    public function type()
    {
        return view(self::FOLDER . 'type.main');
    }

    public function segment()
    {
        return view(self::FOLDER . 'segment.main');
    }
}
