<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Item::select('*');

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

    public function store(ItemRequest $request)
    {
        Item::updateOrCreate(
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
        $row = Item::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        Item::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Item::orderBy('name')->get();

        return response()->json($rows);
    }
}
