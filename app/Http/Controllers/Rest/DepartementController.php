<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepartementRequest;
use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Departement::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('code', 'like', "%{$search}%")
                ->orWhere('name', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(DepartementRequest $request)
    {
        Departement::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'desc' => $request->desc,
                'active' => $request->active == 'true' ? 1 : 0,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = Departement::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        Departement::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Departement::where('active', 1)
                ->orderBy('name')->get();
    
        return response()->json($rows, 200);
    }
}
