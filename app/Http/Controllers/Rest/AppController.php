<?php

namespace App\Http\Controllers\Rest;

use App\Helpers\Generated;
use App\Http\Controllers\Controller;
use App\Models\AppMenu;
use Illuminate\Http\Request;

class AppController extends Controller
{
    //
    public function menu()
    {
        $rows = AppMenu::where('active', 1)->orderBy('text', 'asc')->get();
        $result = Generated::buildTree($rows);

        return response()->json($result);
    }
}
