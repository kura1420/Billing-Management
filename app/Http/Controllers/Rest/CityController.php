<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\CityRequest;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = City::join('provinsis', 'provinsis.id', '=', 'cities.provinsi_id')
            ->select([
                'cities.id',
                'cities.name',
                    'provinsis.name as provinsi_id'
            ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('cities.name', 'like', "%{$search}%")
                ->where('provinsis.name', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(CityRequest $request)
    {
        City::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'provinsi_id' => $request->provinsi_id,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = City::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        City::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists(Request $request)
    {
        $val = Validator::make($request->all(), [
            'provinsi_id' => 'required|string'
        ]);

        if ($val->fails()) {
            return response()->json($val->errors());
        } else {
            $rows = City::where('provinsi_id', $request->provinsi_id)
                ->orderBy('name')->get();
    
            return response()->json($rows, 200);
        }
    }
}
