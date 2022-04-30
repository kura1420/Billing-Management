<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use Illuminate\Http\Request;

class BillingInvoiceController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = BillingInvoice::join('billing_types', 'billing_invoices.billing_type_id', '=', 'billing_types.id')
            ->join('customer_data', 'billing_invoices.customer_data_id', '=', 'customer_data.id')
            ->join('product_types', 'billing_invoices.product_type_id', '=', 'product_types.id')
            ->join('product_services', 'billing_invoices.product_service_id', '=', 'product_services.id')
            ->select([
                'billing_invoices.id',
                'billing_invoices.code',
                'billing_invoices.status',
                'billing_invoices.price_ppn',
                'billing_invoices.price_sub',
                'billing_invoices.price_total',
                'billing_invoices.price_discount',
                'billing_invoices.created_at',
                'billing_invoices.updated_at',
                    'billing_types.name as billing_type_id',
                        'customer_data.name as customer_data_id',
                            'product_types.name as product_type_id',
                                'product_services.name as product_service_id',
            ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('billing_invoices.code', 'like', "%{$search}%")
                ->orWhere('billing_types.name', 'like', "%{$search}%")
                ->orWhere('customer_data.name', 'like', "%{$search}%")
                ->orWhere('product_types.name', 'like', "%{$search}%")
                ->orWhere('product_services.name', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(Request $request)
    {
        # code...
    }

    public function show($id)
    {
        $row = BillingInvoice::find($id);

        return $row;
    }
}
