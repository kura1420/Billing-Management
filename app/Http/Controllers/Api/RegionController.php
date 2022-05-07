<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    //
    public function provinsi(Request $request)
    {
        $id = $request->id ?? NULL;

        if ($id) {
            $rows = Provinsi::find($id);
        } else {
            $rows = Provinsi::orderBy('name', 'asc')->get();
        }

        return response()->json($rows);
    }
}
