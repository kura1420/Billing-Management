<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartnerController extends Controller
{
    //
    const FOLDER = 'pages.partner.';

    public function index()
    {
        return view(self::FOLDER . 'data.main');
    }
}
