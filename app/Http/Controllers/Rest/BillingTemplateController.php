<?php

namespace App\Http\Controllers\Rest;

use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\BillingTemplateRequest;
use App\Models\BillingTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillingTemplateController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = BillingTemplate::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('sender', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }
    
    public function store(BillingTemplateRequest $request)
    {
        BillingTemplate::updateOrCreate(
            [
                'sender' => $request->sender,
                'type' => $request->type,
            ],
            [
                'name' => $request->name,                
                'content' => $request->content,                
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = BillingTemplate::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        BillingTemplate::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = BillingTemplate::orderBy('name')->get();

        return response()->json($rows);
    }

    public function preview(Request $request)
    {
        $val = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($val->fails()) {
            return response()->json($val->errors());
        } else {
            $content = $request->content;

            $productType = \App\Models\ProductType::inRandomOrder()->first();

            $price_sub = 10000000;
            $price_ppn = (11 / 100) * $price_sub;
            $price_total = round($price_sub + $price_ppn, 2);

            $today = date('Y-m-d');

            $search = [
                '_invoice_code_',
                '_start_date_',
                '_end_date_',
                
                '_price_sub_',
                '_price_ppn_',
                '_price_total_',

                '_product_service_',
                '_customer_code_',
                '_customer_name_',
            ];

            $replace = [
                'INV/' . date('Ymd') . '/' . str_pad(rand(1111, 9999), 4, '0', STR_PAD_LEFT),
                date('d F Y', strtotime($today)),
                date('d F Y', strtotime(str_replace('-', '/', $today) . "+7 days")),

                Formatter::rupiah($price_sub),
                Formatter::rupiah($price_ppn),
                Formatter::rupiah($price_total),
                
                \App\Models\ProductService::where('product_type_id', $productType->id)->inRandomOrder()->first()->name,
                uniqid(),
                'Robert',
            ];

            $template = str_replace($search, $replace, $content);

            return view('billing.template.preview', compact('template'));
        }        
    }
}
