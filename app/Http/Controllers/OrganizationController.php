<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    //
    const FOLDER = 'pages.organization.';

    public function departement()
    {
        return view(self::FOLDER . 'departement.main');
    }
}
