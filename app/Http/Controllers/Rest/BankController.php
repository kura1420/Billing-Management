<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankRequest;
use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Bank::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('responsible_name', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(BankRequest $request)
    {
        Bank::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'code' => $request->code,
                'active' => $request->active == 'true' ? 1 : 0,
                'responsible_name' => $request->responsible_name,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = Bank::find($id);
        $row->active = $row->active ? 'on' : 'off';

        return $row;
    }
    
    public function destroy($id)
    {
        Bank::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Bank::orderBy('name')->get();

        return response()->json($rows);
    }
}
