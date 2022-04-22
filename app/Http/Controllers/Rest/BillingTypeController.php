<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillingTypeRequest;
use App\Models\BillingType;
use Illuminate\Http\Request;

class BillingTypeController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = BillingType::select('*');

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
    
    public function store(BillingTypeRequest $request)
    {
        BillingType::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'desc' => $request->desc,
                'notif' => $request->notif,
                'suspend' => $request->suspend,
                'terminated' => $request->terminated,
                'active' => $request->active == 'true' ? 1 : 0,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = BillingType::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        BillingType::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = BillingType::orderBy('name')->get();

        return response()->json($rows);
    }
}
