<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    //
    const FOLDER = 'pages.inventory.';

    public function unit()
    {
        return view(self::FOLDER . 'unit.main');
    }

    public function item()
    {
        return view(self::FOLDER . 'item.main');
    }
}
