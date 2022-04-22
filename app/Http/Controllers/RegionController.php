<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RegionController extends Controller
{
    //
    const FOLDER = 'region.';

    public function provinsi()
    {
        return view(self::FOLDER . 'provinsi.main');
    }

    public function city()
    {
        return view(self::FOLDER . 'city.main');
    }
}
