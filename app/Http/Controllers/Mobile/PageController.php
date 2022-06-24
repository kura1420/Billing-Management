<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    //
    const FOLDER = 'mobile.pages.';

    public function index()
    {
        $user_id = session()->get('user_data')['id'];

        return view(self::FOLDER . 'index', compact('user_id'));
    }
}
