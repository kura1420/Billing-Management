<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoreController extends Controller
{
    //
    const FOLDER = 'core.';

    public function profile()
    {
        return view(self::FOLDER . 'profile.main');
    }
    
    public function user()
    {
        return view(self::FOLDER . 'user.main');
    }

    public function role()
    {
        return view(self::FOLDER . 'role.main');
    }

    public function menu()
    {
        return view(self::FOLDER . 'menu.main');
    }
}
