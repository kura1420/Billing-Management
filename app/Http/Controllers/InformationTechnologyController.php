<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InformationTechnologyController extends Controller
{
    //
    const FOLDER = 'pages.information_technology.';

    public function router_site()
    {
        return view(self::FOLDER . 'router_site.main');
    }

    public function radius_api()
    {
        return view(self::FOLDER . 'radius_api.main');
    }
}
