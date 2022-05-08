<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\AreaRequest;
use App\Models\Area;
use App\Models\AreaProduct;
use App\Models\AreaProductCustomer;
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
            $result = $table->where('areas.name', 'like', "%{$search}%")
                ->orWhere('areas.code', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }
    
    public function store(AreaRequest $request)
    {
        $products = json_decode($request->products, TRUE);
        $customers = json_decode($request->customers, TRUE);

        $area = Area::updateOrCreate(
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

        if (!empty($products)) {
            foreach ($products as $key => $value) {
                $ppn_tax = \App\Models\Tax::find($request->ppn_tax_id)->first();
                $productService = \App\Models\ProductService::where('id', $value['product_service_id'])->first();

                $sub = $productService->price;
                $ppn = ($ppn_tax->value / 100) * $sub;
                $total = $sub + $ppn;

                if (empty($value['id'])) {
                    AreaProduct::create([
                        'area_id' => $area->id,
                        'provinsi_id' => $value['provinsi_id'],
                        'city_id' => $value['city_id'],
                        'product_type_id' => $value['product_type_id'],
                        'product_service_id' => $value['product_service_id'],
                        'active' => $value['active'] == 'true' || $value['active'] == 1 ? 1 : 0,
                        'price_sub' => $sub,
                        'price_ppn' => $ppn,
                        'price_total' => $total,
                    ]);
                } else {
                    AreaProduct::find($value['id'])
                        ->update([
                            'provinsi_id' => $value['provinsi_id'],
                            'city_id' => $value['city_id'],
                            'product_type_id' => $value['product_type_id'],
                            'product_service_id' => $value['product_service_id'],
                            'active' => $value['active'] == 'true' || $value['active'] == 1 ? 1 : 0,
                            'price_sub' => $sub,
                            'price_ppn' => $ppn,
                            'price_total' => $total,
                        ]);
                }      
            }
        }

        if (!empty($customers)) {
            foreach ($customers as $key => $value) {
                if (empty($value['id'])) {
                    AreaProductCustomer::create([
                        'area_id' => $area->id,
                        'provinsi_id' => $value['provinsi_id'],
                        'city_id' => $value['city_id'],
                        'product_type_id' => $value['product_type_id'],
                        'product_service_id' => $value['product_service_id'],
                        'customer_type_id' => $value['customer_type_id'],
                        'customer_segment_id' => $value['customer_segment_id'],
                        'area_product_id' => $value['area_product_id'],
                        'active' => $value['active'] == 'true' || $value['active'] == 1 ? 1 : 0,
                    ]);
                } else {
                    AreaProductCustomer::find($value['id'])
                        ->update([
                            'provinsi_id' => $value['provinsi_id'],
                            'city_id' => $value['city_id'],
                            'product_type_id' => $value['product_type_id'],
                            'product_service_id' => $value['product_service_id'],
                            'customer_type_id' => $value['customer_type_id'],
                            'customer_segment_id' => $value['customer_segment_id'],
                            'area_product_id' => $value['area_product_id'],
                            'active' => $value['active'] == 'true' || $value['active'] == 1 ? 1 : 0,
                        ]);
                }  
            }
        }

        $status = $request->id ? 200 : 201;

        return response()->json($area, $status);
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
        $rows = Area::where('active', 1)->orderBy('name')->get();

        return response()->json($rows);
    }

    public function productLists($id)
    {
        $rows = AreaProduct::join('provinsis', 'area_products.provinsi_id', '=', 'provinsis.id')
            ->join('cities', 'area_products.city_id', '=', 'cities.id')
            ->join('product_types', 'area_products.product_type_id', '=', 'product_types.id')
            ->join('product_services', 'area_products.product_service_id', '=', 'product_services.id')
            ->where('area_products.area_id', $id)
            ->select([
                'area_products.id',
                'area_products.area_id',
                'area_products.provinsi_id',
                'area_products.city_id',
                'area_products.product_type_id',
                'area_products.product_service_id',
                'area_products.active',
                'area_products.price_sub',
                'area_products.price_ppn',
                'area_products.price_total',
                'area_products.created_at',
                'area_products.updated_at',
                    'provinsis.name as provinsi_name',
                        'cities.name as city_name',
                            'product_types.name as product_type_name',
                                'product_services.name as product_service_name'
            ])
            ->get();

        return response()->json($rows);
    }

    public function productDestroy($id)
    {
        AreaProduct::find($id)->delete();

        return response()->json('OK', 200);
    }
    
    public function customerLists($id)
    {
        if ($id == 'clear') {
            return response()->json([], 200);
        }         

        $rows = AreaProductCustomer::join('provinsis', 'area_product_customers.provinsi_id', '=', 'provinsis.id')
            ->join('cities', 'area_product_customers.city_id', '=', 'cities.id')
            ->join('product_types', 'area_product_customers.product_type_id', '=', 'product_types.id')
            ->join('product_services', 'area_product_customers.product_service_id', '=', 'product_services.id')
            ->join('customer_types', 'area_product_customers.customer_type_id', '=', 'customer_types.id')
            ->join('customer_segments', 'area_product_customers.customer_segment_id', '=', 'customer_segments.id')
            ->where('area_product_customers.area_id', $id)
            ->select([
                'area_product_customers.id',
                'area_product_customers.provinsi_id',
                'area_product_customers.city_id',
                'area_product_customers.product_type_id',
                'area_product_customers.product_service_id',
                'area_product_customers.customer_type_id',
                'area_product_customers.customer_segment_id',
                'area_product_customers.area_product_id',
                'area_product_customers.active',
                'area_product_customers.created_at',
                'area_product_customers.updated_at',
                    'provinsis.name as provinsi_name',
                        'cities.name as city_name',
                            'product_types.name as product_type_name',
                                'product_services.name as product_service_name',
                                    'customer_types.name as customer_type_name',
                                        'customer_segments.name as customer_segment_name',
            ])
            ->get();

        return response()->json($rows);
    }

    public function customerDestroy($id)
    {
        AreaProductCustomer::find($id)->delete();

        return response()->json('OK', 200);
    }
}
