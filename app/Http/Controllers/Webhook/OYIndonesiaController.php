<?php

namespace App\Http\Controllers\Webhook;

use App\Helpers\RestApi;
use App\Http\Controllers\Controller;
use App\Models\AppProfile;
use Illuminate\Http\Request;
use App\Models\BillingInvoice;
use App\Models\CustomerContact;
use App\Models\CustomerProfile;
use App\Models\InvoiceTransactionMode;
use Carbon\Carbon;

class OYIndonesiaController extends Controller
{
    //
    public function fundAcceptancePaymentLinkCreate($id, $code)
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

        $paymentLinkCreate = TRUE;
        if (count($row->invoice_transaction_modes)>0) {
            if ($row->invoice_transaction_modes->trx_id) {
                $endpoint = '/payment-checkout/' . $row->invoice_transaction_modes->trx_id;
                $method = 'GET';
                $params = NULL;
    
                $res = RestApi::run($endpoint, $method, $params);
    
                if ($res->status) {
                    $paymentLinkCreate = FALSE;
    
                    return redirect($row->invoice_transaction_modes->trx_payment);
                }            
            }
        }

        if ($paymentLinkCreate) {
            $endpoint = '/payment-checkout/create-v2';
            $method = 'POST';
            $params = [
                'partner_tx_id' => $row->id,
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
                InvoiceTransactionMode::updateOrCreate(
                    [
                        'billing_invoice_id' => $row->id,
                    ],
                    [
                        'mode' => 'payment_link',
                        'trx_id' => $res->payment_link_id,
                        'trx_payment' => $res->url,
                        'trx_bank_code' => NULL,
                    ]
                );

                return redirect($res->url);
            } else {
                $message = $res->message ?? NULL;

                return view('errors.payment_gateway.invalid_format', compact('message'));
            }
        }
    }

    public function fundAcceptanceCallback(Request $request)
    {
        $tx_ref_number = $request->tx_ref_number ?? NULL;

        return response()->json($tx_ref_number);
    }

    public function staticVACallback(Request $request)
    {
        $trx_id = $request->trx_id ?? NULL;

        return response()->json($trx_id);
    }
}
