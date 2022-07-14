<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemDataController extends Controller
{
    //
    public function lists()
    {
        $rows = Item::orderBy('name', 'asc')
            ->get()
            ->map(function($row) {
                $row->partner_code = $row->partners()->first()->code;
                $row->partner_name = $row->partners()->first()->name;

                $row->unit_shortname = $row->units()->first()->shortname;
                $row->unit_name = $row->units()->first()->name;

                return $row;
            });

        return response()->json($rows);
    }

    public function brands()
    {
        $rows = Item::select('brand')
            ->distinct()
            ->get()
            ->map(fn($row) => ['id' => $row->brand, 'text' => $row->brand]);

        return response()->json($rows);
    }
}
