<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSegmentRequest;
use App\Models\CustomerSegment;
use Illuminate\Http\Request;

class CustomerSegmentController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = CustomerSegment::join('customer_types', 'customer_segments.customer_type_id', '=', 'customer_types.id')
            ->select([
                'customer_segments.id',
                'customer_segments.code',
                'customer_segments.name',
                'customer_segments.desc',
                'customer_segments.active',
                'customer_segments.custom_price',
                    'customer_types.name as customer_type_id',
            ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('desc', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }
    
    public function store(CustomerSegmentRequest $request)
    {
        CustomerSegment::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'desc' => $request->desc,
                'active' => $request->active == 'true' ? 1 : 0,
                'custom_price' => $request->custom_price == 'true' ? 1 : 0,
                'customer_type_id' => $request->customer_type_id,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = CustomerSegment::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        CustomerSegment::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = CustomerSegment::where('active', 1)->orderBy('name')->get();

        return response()->json($rows);
    }
}
