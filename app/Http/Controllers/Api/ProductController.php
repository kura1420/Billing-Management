<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductPromo;
use App\Models\ProductService;
use App\Models\ProductType;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function type(Request $request)
    {
        $query = $request->q ?? NULL;

        $table = ProductType::select('id', 'code', 'name', 'desc', 'active');

        if ($query) {
            $table->where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%")
                ->orWhere('desc', 'like', "%{$query}%");
        }

        $rows = $table->orderBy('name', 'asc')
            ->get();

        return response()->json($rows);
    }

    public function service(Request $request)
    {
        $query = $request->q ?? NULL;

        $table = ProductService::join('product_types', 'product_services.product_type_id', '=', 'product_types.id')
            ->select([
                'product_services.id',
                'product_services.code',
                'product_services.name',
                'product_services.desc',
                'product_services.active',
                'product_services.price',
                    'product_types.name as product_type',
            ]);

        if ($query) {
            $table->where('product_services.name', 'like', "%{$query}%")
                ->orWhere('product_services.code', 'like', "%{$query}%")
                ->orWhere('product_types.name', 'like', "%{$query}%");
        }

        $rows = $table->orderBy('product_services.name', 'asc')
            ->get();

        return response()->json($rows);
    }

    public function promos(Request $request)
    {
        $query = $request->q ?? NULL;

        $table = ProductPromo::select([
            'id',
            'code',
            'name',
            'desc',
            'active',
            'start',
            'end',
            'image',
            'type',
            'discount',
            'until_payment',
        ]);

        if ($query) {
            $table->where('name', 'like', "%{$query}%")
                ->orWhere('code', 'like', "%{$query}%");
        }

        $rows = $table->orderBy('name', 'asc')
            ->get();

        return response()->json($rows);
    }
}
