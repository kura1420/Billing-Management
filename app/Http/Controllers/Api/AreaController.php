<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\AreaProduct;
use App\Models\AreaProductCustomer;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    //
    public function index(Request $request)
    {
        $query = $request->q ?? NULL;

        $table = Area::join('taxes', 'areas.ppn_tax_id', '=', 'taxes.id')
            ->select([
                'areas.id',
                'areas.code',
                'areas.name',
                'areas.active',
                    'taxes.name as ppn_name',
                    'taxes.value as ppn_value',
            ]);

        if ($query) {
            $table->where('areas.name', 'like', "%{$query}%")
                ->orWhere('areas.code', 'like', "%{$query}%");
        }

        $areas = $table->orderBy('areas.name', 'asc')
            ->get();

        $rows = [];
        foreach ($areas as $area) {
            $area->products = $this->products($area->id);
            $area->customer_segments = $this->customer_segments($area->id);

            $rows[] = $area;
        }

        return response()->json($rows);
    }

    protected function products($area_id)
    {
        $result = AreaProduct::join('provinsis', 'area_products.provinsi_id', '=', 'provinsis.id')
            ->join('cities', 'area_products.city_id', '=', 'cities.id')
            ->join('product_types', 'area_products.product_type_id', '=', 'product_types.id')
            ->join('product_services', 'area_products.product_service_id', '=', 'product_services.id')
            ->where('area_products.area_id', $area_id)
            ->select([
                'area_products.id',
                'area_products.active',
                'area_products.price_sub',
                'area_products.price_ppn',
                'area_products.price_total',
                    'provinsis.name as provinsi',
                        'cities.name as city',
                            'product_types.name as product_type',
                                'product_services.name as product_service'
            ])
            ->get();

        return $result;
    }

    protected function customer_segments($area_id)
    {
        $result = AreaProductCustomer::join('provinsis', 'area_product_customers.provinsi_id', '=', 'provinsis.id')
            ->join('cities', 'area_product_customers.city_id', '=', 'cities.id')
            ->join('product_types', 'area_product_customers.product_type_id', '=', 'product_types.id')
            ->join('product_services', 'area_product_customers.product_service_id', '=', 'product_services.id')
            ->join('customer_types', 'area_product_customers.customer_type_id', '=', 'customer_types.id')
            ->join('customer_segments', 'area_product_customers.customer_segment_id', '=', 'customer_segments.id')
            ->where('area_product_customers.area_id', $area_id)
            ->select([
                'area_product_customers.id',
                'area_product_customers.active',
                    'provinsis.name as provinsi',
                        'cities.name as city',
                            'product_types.name as product_type',
                                'product_services.name as product_service',
                                    'customer_types.name as customer_type',
                                        'customer_segments.name as customer_segment',
            ])
            ->get();

        return $result;
    }
}
