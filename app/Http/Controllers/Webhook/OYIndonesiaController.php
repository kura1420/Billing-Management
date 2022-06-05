<?php

namespace App\Http\Controllers\Webhook;

use App\Helpers\Formatter;
use App\Helpers\RestApi;
use App\Http\Controllers\Controller;
use App\Models\AppProfile;
use Illuminate\Http\Request;
use App\Models\BillingInvoice;
use App\Models\BillingTemplate;
use App\Models\CustomerContact;
use App\Models\CustomerProfile;
use App\Models\InvoiceTransactionMode;
use App\Notifications\InvoicePaid;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OYIndonesiaController extends Controller
{
    //
    public function fundAcceptancePaymentLinkCreate($id, $code, $mode)
    {
        $appProfile = AppProfile::first();

        $row = BillingInvoice::with(['customer_data', 'invoice_transaction_modes'])
            ->whereRaw("(id='$id' AND code='$code' AND status=0) OR (id='$id' AND code='$code' AND status=2)")
            ->first();  

        $customerProfile = CustomerProfile::where('customer_data_id', $row->customer_data->id)->first();
        $customerContacts = CustomerContact::where('customer_data_id', $row->customer_data->id)->limit(2)->get();

        $emails = [];
        array_push($emails, $customerProfile->email);

        if (count($customerContacts)>0) {
            foreach ($customerContacts as $key => $customerContact) {
                array_push($emails, $customerContact->email);
            }
        }

        switch ($mode) {
            case 'create':
                $paymentLinkCreate = TRUE;
                if (count($row->invoice_transaction_modes)>0) {
                    $invoice_transaction_mode = $row->invoice_transaction_modes[0];

                    if ($invoice_transaction_mode->trx_id) {
                        $endpoint = '/payment-checkout/' . $invoice_transaction_mode->trx_id;
                        $method = 'GET';
                        $params = NULL;
            
                        $res = RestApi::run($endpoint, $method, $params);
            
                        if ($res->status) {
                            $paymentLinkCreate = FALSE;

                            $data = $res->data;
                            switch ($data->status) {
                                case 'WAITING_PAYMENT':
                                    return redirect($invoice_transaction_mode->trx_payment);
                                    break;

                                case 'EXPIRED':
                                case 'FAILED':
                                    InvoiceTransactionMode::where('id', $invoice_transaction_mode->id)
                                        ->update([
                                            'status' => $data->status,
                                        ]);

                                    $recreateCardPayment = url("/pg/fund-acceptance/payment-link/$id/$code/recreate");

                                    $message = 'Pembayaran Anda "Expired/FAILED" silahkan klik <a href="'.$recreateCardPayment.'" style="font-weight: bold;">Buat Pembayaran</a> untuk mendapatkannya kembali.';
                                    
                                    return view('errors.payment_gateway.message', compact('message'));                            
                                    break;
                                
                                default:
                                    return abort(404);
                                    break;
                            }
                        } else {
                            $message = 'Gagal membuat koneksi ke payment gateway, silahkan <a href="javascript:void(0)" onclick="window.location.reload()" style="font-weight: bold;">refresh/perbarui</a> halaman.';
    
                            return view('errors.payment_gateway.message', compact('message'));
                        }       
                    } else {
                        $message = 'Gagal membuat card payment, silahkan <a href="javascript:void(0)" onclick="window.location.reload()" style="font-weight: bold;">refresh/perbarui</a> halaman.';

                        return view('errors.payment_gateway.message', compact('message'));
                    }
                }

                if ($paymentLinkCreate) {
                    $invoiceTransactionModeId = Str::uuid();

                    $endpoint = '/payment-checkout/create-v2';
                    $method = 'POST';
                    $params = [
                        'partner_tx_id' => $invoiceTransactionModeId,
                        'child_balance' => NULL,
                        'description' => 'Invoice periode ' . date('F Y', strtotime($row->notif_at)),
                        'notes' => 'Invoice Create ' . date('d F Y', strtotime($row->notif_at)) . ' Due Date ' . date('d F Y', strtotime($row->suspend_at)),
                        'sender_name' => $appProfile->name,
                        'amount' => (int) $row->price_total,
                        'email' => implode(';', $emails),
                        'phone_number' => $customerProfile->handphone,
                        'is_open' => TRUE,
                        'include_admin_fee' => FALSE,
                        'list_disabled_payment_methods' => '',
                        'list_enabled_banks' => '',
                        'list_enabled_ewallet' => '',
                        'expiration' => Carbon::now()->addHours(2)->format('Y-m-d H:m:s'),
                        'due_date' => $row->suspend_at,
                        'va_display_name' => $customerProfile->name,
                    ];
        
                    $res = RestApi::run($endpoint, $method, $params);
        
                    if ($res->status) {                
                        InvoiceTransactionMode::create([
                            'id' => $invoiceTransactionModeId,
                            'billing_invoice_id' => $row->id,
                            'mode' => 'payment_link',
                            'trx_id' => $res->payment_link_id,
                            'trx_payment' => $res->url,
                            'trx_bank_code' => NULL,
                            'response' => json_encode($res),
                        ]);
        
                        return redirect($res->url);
                    } else {
                        $message = $res->message ?? NULL;

                        return view('errors.payment_gateway.message', compact('message'));
                    }
                } else {
                    return abort(404);
                }
                break;

            case 'recreate':
                if (count($row->invoice_transaction_modes)>0) {
                    $invoice_transaction_mode = $row->invoice_transaction_modes[0];

                    if ($invoice_transaction_mode->trx_id) {
                        $endpoint = '/payment-checkout/' . $invoice_transaction_mode->trx_id;
                        $method = 'GET';
                        $params = NULL;
            
                        $res = RestApi::run($endpoint, $method, $params);

                        if ($res->status) {
                            $data = $res->data;

                            if ($data->status == 'EXPIRED' || $data->status == 'FAILED') {
                                $invoiceTransactionModeId = Str::uuid();

                                $endpoint = '/payment-checkout/create-v2';
                                $method = 'POST';
                                $params = [
                                    'partner_tx_id' => $invoiceTransactionModeId,
                                    'child_balance' => NULL,
                                    'description' => 'Invoice periode ' . date('F Y', strtotime($row->notif_at)),
                                    'notes' => 'Invoice Create ' . date('d F Y', strtotime($row->notif_at)) . ' Due Date ' . date('d F Y', strtotime($row->suspend_at)),
                                    'sender_name' => $appProfile->name,
                                    'amount' => (int) $row->price_total,
                                    'email' => implode(';', $emails),
                                    'phone_number' => $customerProfile->handphone,
                                    'is_open' => TRUE,
                                    'include_admin_fee' => FALSE,
                                    'list_disabled_payment_methods' => '',
                                    'list_enabled_banks' => '',
                                    'list_enabled_ewallet' => '',
                                    'expiration' => Carbon::now()->addHours(2)->format('Y-m-d H:m:s'),
                                    'due_date' => $row->suspend_at,
                                    'va_display_name' => $customerProfile->name,
                                ];
                    
                                $res = RestApi::run($endpoint, $method, $params);
                    
                                if ($res->status) {                  
                                    InvoiceTransactionMode::create([
                                        'id' => $invoiceTransactionModeId,
                                        'billing_invoice_id' => $row->id,
                                        'mode' => 'payment_link',
                                        'trx_id' => $res->payment_link_id,
                                        'trx_payment' => $res->url,
                                        'trx_bank_code' => NULL,
                                        'response' => json_encode($res),
                                    ]);
                    
                                    return redirect($res->url);
                                } else {
                                    $message = $res->message ?? NULL;
                    
                                    return view('errors.payment_gateway.message', compact('message'));
                                }
                            } else {
                                return abort(404);
                            }                            
                        } else {
                            $message = 'Gagal membuat card payment, silahkan <a href="javascript:void(0)" onclick="window.location.reload()" style="font-weight: bold;">refresh/perbarui</a> halaman.';
    
                            return view('errors.payment_gateway.message', compact('message'));
                        }                        
                    } else {
                        return abort(404);
                    }
                } else {
                    return abort(404);
                }
                break;
            
            default:
                return abort(404);
                break;
        }
    }

    public function fundAcceptanceCallback(Request $request)
    {
        $res = $request->all();
        $id = $request->partner_tx_id;
        $tx_ref_number = $request->tx_ref_number;
        $status = $request->status;
        $sender_bank = $request->sender_bank;
        $payment_method = $request->payment_method;

        $invoiceTransactionMode = InvoiceTransactionMode::find($id);
        
        $billingInvoice = BillingInvoice::with([
            'customer_data',
            'product_types',
            'product_services',
        ])->where('id' , $invoiceTransactionMode->billing_invoice_id)->first();

        $customerProfile = $billingInvoice->customer_data->customer_profiles()->first();

        $replace = [
            $billingInvoice->code,
            date('d M Y', strtotime($billingInvoice->notif_at)),
            date('d M Y', strtotime($billingInvoice->suspend_at)),

            Formatter::rupiah($billingInvoice->price_sub),
            Formatter::rupiah($billingInvoice->price_ppn),
            Formatter::rupiah($billingInvoice->price_total),

            $billingInvoice->product_services->name,
            $billingInvoice->customer_data->code,
            $customerProfile->name,
        ];
        
        $billingInvoice->update([
            'status' => 1,
            'verif_payment_at' => Carbon::now(),
            'type_payment' => 'PG',
        ]);
        
        $invoiceTransactionMode->update([
            'tx_ref_number' => $tx_ref_number,
            'status' => $status,
            'sender_bank' => $sender_bank,
            'payment_method' => $payment_method,
            'response' => json_encode($res),
        ]);

        $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'paid')->first();
    
        $emailBody = NULL;

        if (!empty($billingTemplate)) {
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

            $emailBody = str_replace($search, $replace, $billingTemplate->content);
        }

        $customerProfile->notify(new InvoicePaid($replace, $emailBody));

        return response()->json('OK', 200);
    }

    public function staticVACallback(Request $request)
    {
        $trx_id = $request->trx_id ?? NULL;

        return response()->json($trx_id);
    }
}
