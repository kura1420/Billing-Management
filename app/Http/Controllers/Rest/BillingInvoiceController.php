<?php

namespace App\Http\Controllers\Rest;

use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use App\Models\BillingInvoice;
use App\Models\BillingTemplate;
use App\Notifications\InvoicePaid;
use App\Notifications\Unsuspend;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            ->join('customer_profiles', 'billing_invoices.customer_data_id', '=', 'customer_profiles.customer_data_id')
            ->select([
                'billing_invoices.id',
                'billing_invoices.code',
                'billing_invoices.status',
                'billing_invoices.price_ppn',
                'billing_invoices.price_sub',
                'billing_invoices.price_total',
                'billing_invoices.price_discount',
                'billing_invoices.notif_at',
                'billing_invoices.verif_payment_at',
                    'billing_types.name as billing_type_id',
                        'customer_data.code as customer_data_id',
                            'product_types.name as product_type_id',
                                'product_services.name as product_service_id',
                                    'customer_profiles.name',
                                    'customer_profiles.email'
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

    public function show($id)
    {
        $row = BillingInvoice::with([
            'customer_data',
            'billing_types',
            'product_types',
            'product_services',
            'users'
        ])->find($id);
        
        $customerProfile = $row->customer_data->customer_profiles()->select([
            'name', 
            'email', 
            'handphone', 
            'telp', 
        ])->first();

        $obj = new \stdClass;
        
        $obj->id = $row->id;
        $obj->billing_type_id = $row->billing_types->name;
        $obj->code = $row->code;
        $obj->status = $row->status;
        $obj->price_ppn = $row->price_ppn;
        $obj->price_sub = $row->price_sub;
        $obj->price_total = $row->price_total;
        $obj->price_discount = (int)$row->price_discount;
        $obj->verif_payment_at = $row->verif_payment_at;
        $obj->verif_by_user_id = $row->users ? $row->users->name : NULL;
        $obj->notif_at = $row->notif_at;
        $obj->file_payment = NULL;
        $obj->file_invoice_url = url('/rest/billing-invoice/viewfile/' . $row->id . '/invoice/' . $row->file_invoice);
        $obj->file_payment_url = empty($row->file_payment) ? NULL : url('/rest/billing-invoice/viewfile/' . $row->id . '/bukti/' . $row->file_payment);         

        $obj->product_type_name = $row->product_types->name;
        $obj->product_service_name = $row->product_services->name;
        $obj->customer_data_code = $row->customer_data->code;
        $obj->name = $customerProfile->name;
        $obj->email = $customerProfile->email;
        $obj->telp = $customerProfile->telp;
        $obj->handphone = $customerProfile->handphone;
        $obj->member_at = $row->customer_data->member_at;
        $obj->suspend_at = $row->customer_data->suspend_at;
        $obj->terminate_at = $row->customer_data->terminate_at;

        return $obj;
    }

    public function verif(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_payment' => 'required|mimes:jpeg,jpg,png,pdf',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);
        } else {
            $id = $request->id ?? NULL;

            $row = BillingInvoice::with([
                'customer_data',
                'product_types',
                'product_services',
            ])->find($id);
            
            $customerProfile = $row->customer_data->customer_profiles()->first();

            $file_payment = $row->file_payment ?? uniqid();
            $file_payment = $file_payment . '.' . $request->file_payment->extension();
            $request->file('file_payment')->storeAs(
                'billing/invoice-paid/' . date('Y-m') .'/',
                $file_payment
            );

            $payment_at = Carbon::now();

            $replace = [
                $row->code,
                $row->notif_at,
                $payment_at,
    
                Formatter::rupiah($row->price_sub),
                Formatter::rupiah($row->price_ppn),
                Formatter::rupiah($row->price_total),
    
                $row->product_services->name,
                $row->customer_data->code,
                $customerProfile->name,
            ];

            $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'paid')->first();

            $emailBody = NULL;
            if (!empty($billingTemplate)) {
                $search = [
                    '_invoice_code_',
                    '_invoice_date_',
                    '_invoice_due_',
        
                    '_price_sub_',
                    '_price_ppn_',
                    '_price_total_',
        
                    '_product_service_',
                    '_customer_code_',
                    '_customer_name_',
                ];
    
                $emailBody = str_replace($search, $replace, $billingTemplate->content);
            }            

            $customerProfile->notify(new InvoicePaid($replace, $emailBody));

            $row->update([
                'status' => 1,
                'suspend_at' => NULL,
                'verif_payment_at' => $payment_at,
                'verif_by_user_id' => session()->get('user_data')['id'],
                'file_payment' => $file_payment,
                'type_payment' => 'manual',
            ]);

            return response()->json('OK', 200);
        }
    }

    public function unsuspend($id)
    {
        $row = BillingInvoice::with([
            'customer_data',
            'product_types',
            'product_services',
        ])
        ->where('id', $id)
        ->where('status', 2)
        ->first();
        
        if (!empty($row)) {
            $customerProfile = $row->customer_data->customer_profiles()->first();

            $unsuspend_at = Carbon::now();
            $resuspend_at = $unsuspend_at->addHours(12);

            $replace = [
                $row->code,
                $unsuspend_at,
                $resuspend_at,

                Formatter::rupiah($row->price_sub),
                Formatter::rupiah($row->price_ppn),
                Formatter::rupiah($row->price_total),

                $row->product_services->name,
                $row->customer_data->code,
                $customerProfile->name,
            ];

            $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'unsuspend')->first();

            $emailBody = NULL;
            if (!empty($billingTemplate)) {
                $search = [
                    '_invoice_code_',
                    '_invoice_date_',
                    '_invoice_due_',
        
                    '_price_sub_',
                    '_price_ppn_',
                    '_price_total_',
        
                    '_product_service_',
                    '_customer_code_',
                    '_customer_name_',
                ];

                $emailBody = str_replace($search, $replace, $billingTemplate->content);
            }            

            $customerProfile->notify(new Unsuspend($replace, $emailBody));

            $row->update([
                'status' => 3,
                'suspend_at' => $resuspend_at,
            ]);

            return response()->json('OK', 200); 
        } else {
            return response()->json([
                'data' => [
                    'id' => 'Invoice tidak dalam status suspend'
                ],
                'status' => 'NOT'
            ], 422);
        }    
    }

    public function viewfile($id, $type, $filename)
    {
        $row = BillingInvoice::find($id);

        switch ($type) {
            case 'invoice':
                $pathfile = Storage::path('billing/invoice/' . date('Y-m', strtotime($row->notif_at)) . '/' . $row->file_invoice);
        
                return response()->file($pathfile);
                break;

            case 'bukti':
                $pathfile = Storage::path('billing/invoice-paid/' . date('Y-m', strtotime($row->verif_payment_at)) . '/' . $row->file_payment);

                return response()->file($pathfile);
                break;

            default:
                return abort(404);
                break;
        }
    }
}
