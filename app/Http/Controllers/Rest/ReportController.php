<?php

namespace App\Http\Controllers\Rest;

use App\Helpers\Generated;
use App\Http\Controllers\Controller;
use App\Models\CustomerData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function totals()
    {
        $active = CustomerData::where('active', 1)->count();
        $suspend = CustomerData::whereNotNull('suspend_at')->count();
        $terminated = CustomerData::where('active', 0)->count();
        $revenue = CustomerData::join('area_products', 'customer_data.area_product_id', '=', 'area_products.id')
            ->where('customer_data.active', 1)
            ->sum('area_products.price_total');

        return response()->json([
            'active' => $active,
            'suspend' => $suspend,
            'terminated' => $terminated,
            'revenue' => $revenue
        ]);
    }

    public function areaChart()
    {
        $rows = DB::select("SELECT 
        a.name,
        SUM(CASE WHEN cd.id IS NOT NULL THEN 1 ELSE 0 END) AS total
        FROM areas a 
        LEFT JOIN customer_data cd ON a.id = cd.area_id AND cd.active = 1
        GROUP BY a.name");
        
        $labels = $colors = $data = [];
        foreach ($rows as $row) {
            $labels[] = $row->name;
            $colors[] = Generated::color();
            $data[] = $row->total;
        }

        $res = [
            'labels' => $labels,
            'colors' => $colors,
            'data' => $data
        ];
        
        return response()->json($res);
    }

    public function productServiceChart()
    {
        $rows = DB::select("SELECT 
        ps.name,
        SUM(CASE WHEN cd.id IS NOT NULL THEN 1 ELSE 0 END) AS total
        FROM product_services ps 
        LEFT JOIN customer_data cd ON ps.id = cd.product_service_id AND cd.active = 1
        GROUP BY ps.name");
        
        $labels = $colors = $data = [];
        foreach ($rows as $row) {
            $labels[] = $row->name;
            $colors[] = Generated::color();
            $data[] = $row->total;
        }

        $res = [
            'labels' => $labels,
            'colors' => $colors,
            'data' => $data
        ];
        
        return response()->json($res);
    }

    public function customerSegmentChart()
    {
        $rows = DB::select("SELECT 
        cs.name,
        SUM(CASE WHEN cd.id IS NOT NULL THEN 1 ELSE 0 END) AS total
        FROM customer_segments cs 
        LEFT JOIN customer_data cd ON cs.id = cd.customer_segment_id AND cd.active = 1
        GROUP BY cs.name");
        
        $labels = $colors = $data = [];
        foreach ($rows as $row) {
            $labels[] = $row->name;
            $colors[] = Generated::color();
            $data[] = $row->total;
        }

        $res = [
            'labels' => $labels,
            'colors' => $colors,
            'data' => $data
        ];
        
        return response()->json($res);
    }

    public function listInvoicePay()
    {
        $rows = DB::select("SELECT 
            bi.price_total, bi.verif_payment_at,  
            cd.code as code_customer, 
            cp.name 
            FROM billing_invoices bi
            inner join customer_data cd on bi.customer_data_id  = cd.id
            inner join customer_profiles cp on cd.id = cp.customer_data_id 
            WHERE bi.status = 1
            ORDER BY bi.verif_payment_at DESC
        LIMIT 10");

        return response()->json($rows);
    }
}
