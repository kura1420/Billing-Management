<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Area::join('taxes', 'areas.ppn_tax_id', '=', 'taxes.id')
            ->select([
                'areas.id',
                'areas.code',
                'areas.name',
                'areas.active',
                    'taxes.name as ppn_tax_id',
            ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }
    
    public function store(AreaRequest $request)
    {
        Area::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'desc' => $request->desc,
                'ppn_tax_id' => $request->ppn_tax_id,
                'active' => $request->active == 'true' ? 1 : 0,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = Area::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        Area::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Area::orderBy('name')->get();

        return response()->json($rows);
    }
}
