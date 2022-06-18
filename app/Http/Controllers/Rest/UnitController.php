<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Unit::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('shortname', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(UnitRequest $request)
    {
        Unit::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'shortname' => $request->shortname,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = Unit::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        Unit::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Unit::orderBy('name')->get();

        return response()->json($rows);
    }
}
