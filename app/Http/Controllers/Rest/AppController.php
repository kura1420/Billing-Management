<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\AppMenu;
use Illuminate\Http\Request;

class AppController extends Controller
{
    //
    public function menu()
    {
        $res = AppMenu::with('children')
            ->whereNull('parent')->get();

        return response()->json($res);
    }
}
