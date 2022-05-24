<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegionController extends Controller
{
    //
    public function provinsi(Request $request)
    {
        $query = $request->q ?? NULL;

        $table = Provinsi::select('id', 'name');

        if ($query) {
            $table->where('name', 'like', "%$query%");
        }

        $rows = $table->orderBy('name', 'asc')
            ->get();

        return response()->json($rows);
    }

    public function city(Request $request)
    {
        $validatior = Validator::make($request->all(), [
            'provinsi' => 'required|string',
        ]);

        if ($validatior->fails()) {
            return response()->json($validatior->errors());
        } else {
            $provinsi = $request->provinsi ?? NULL;
            $query = $request->q ?? NULL;
    
            $table = City::select('id', 'name')
                ->where('provinsi_id', $provinsi);

            if ($query) {
                $table->where('name', 'like', "%$query%");
            }

            $rows = $table->orderBy('name', 'asc')
                ->get();
            
            return response()->json($rows);
        }
    }
}
