<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillingTypeRequest;
use App\Models\BillingProduct;
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
        $products = $request->products;

        $billingType = BillingType::updateOrCreate(
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

        if (count($products)>0) {
            foreach ($products as $key => $value) {
                $check = BillingProduct::where([
                    ['billing_type_id', '=', $billingType->id],
                    ['product_type_id', '=', $value['product_type_id']],
                    ['product_service_id', '=', $value['product_service_id']],
                ])->count();

                if ($check == 0) {
                    if (empty($value['id'])) {
                        BillingProduct::create([
                            'billing_type_id' => $billingType->id,
                            'product_type_id' => $value['product_type_id'],
                            'product_service_id' => $value['product_service_id'],
                        ]);
                    } else {
                        BillingProduct::find($value['id'])
                            ->update([
                                'product_type_id' => $value['product_type_id'],
                                'product_service_id' => $value['product_service_id'],
                            ]);
                    }
                }
            }
        }

        $status = $request->id ? 200 : 201;

        return response()->json($billingType, $status);
    }

    public function show($id)
    {
        $row = BillingType::find($id);
        $row->active = $row->active == 1 ? 'on' : 'off';

        return $row;
    }
    
    public function destroy($id)
    {
        $billingType = BillingType::find($id);

        $billingType->billing_products()->delete();

        $billingType->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = BillingType::where('active', 1)->orderBy('name')->get();

        return response()->json($rows);
    }

    public function productLists($id)
    {
        $rows = BillingProduct::join('product_types', 'billing_products.product_type_id', '=', 'product_types.id')
            ->join('product_services', 'billing_products.product_service_id', '=', 'product_services.id')
            ->where('billing_type_id', $id)
            ->select([
                'billing_products.id',
                'billing_products.billing_type_id',
                'billing_products.product_type_id',
                'billing_products.product_service_id',
                    'product_types.name as product_type_name',
                        'product_services.name as product_service_name'
            ])
            ->get();

        return $rows;
    }

    public function productDestroy($id)
    {
        BillingProduct::find($id)->delete();

        return response()->json('OK', 200);
    }
}
