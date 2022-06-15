<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MikrotikController extends Controller
{
    //
    const FOLDER = 'mikrotik.';

    public function userProfile()
    {
        return view(self::FOLDER . 'user_profile.main');
    }

    public function voucher()
    {
        return view(self::FOLDER . 'voucher.main');
    }

    public function actives()
    {
        return view(self::FOLDER . 'active.main');
    }
}
