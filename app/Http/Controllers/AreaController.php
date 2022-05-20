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
}
