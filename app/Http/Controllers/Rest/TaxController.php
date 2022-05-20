<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaxRequest;
use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Tax::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('value', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(TaxRequest $request)
    {
        Tax::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'value' => $request->value,
                'desc' => $request->desc,
                'type' => $request->type,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = Tax::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        Tax::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Tax::orderBy('name')->get();

        return response()->json($rows);
    }
}
