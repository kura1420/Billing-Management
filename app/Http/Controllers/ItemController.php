<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    //
    const FOLDER = 'item.';

    public function unit()
    {
        return view(self::FOLDER . 'unit.main');
    }
}
