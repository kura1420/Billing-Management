<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductServiceRequest;
use App\Models\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductServiceController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = ProductService::join('product_types', 'product_services.product_type_id', '=', 'product_types.id')
            ->select([
                'product_services.id',
                'product_services.code',
                'product_services.name',
                'product_services.desc',
                'product_services.active',
                'product_services.price',
                    'product_types.name as product_type_id',
            ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('product_services.name', 'like', "%{$search}%")
                ->orWhere('product_services.code', 'like', "%{$search}%")
                ->orWhere('product_types.name', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }
    
    public function store(ProductServiceRequest $request)
    {
        ProductService::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'desc' => $request->desc,
                'active' => $request->active == 'true' ? 1 : 0,
                'price' => $request->price,
                'product_type_id' => $request->product_type_id,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = ProductService::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        ProductService::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists(Request $request)
    {
        $val = Validator::make($request->all(), [
            'product_type_id' => 'required|string',
        ]);

        if ($val->fails()) {
            return response()->json($val->errors());
        } else {
            $rows = ProductService::where('product_type_id', $request->product_type_id)
                ->where('active', 1)
                ->orderBy('name')->get();

            return response()->json($rows);
        }        
    }
}
