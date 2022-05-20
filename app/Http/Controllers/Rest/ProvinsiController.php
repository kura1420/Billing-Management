<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProvinsiRequest;
use App\Models\Provinsi;
use Illuminate\Http\Request;

class ProvinsiController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Provinsi::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(ProvinsiRequest $request)
    {
        Provinsi::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = Provinsi::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        Provinsi::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Provinsi::orderBy('name')->get();

        return response()->json($rows);
    }
}
