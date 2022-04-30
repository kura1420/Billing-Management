<?php

namespace App\Http\Controllers\Test;

use App\Helpers\Formatter;
use App\Http\Controllers\Controller;
use App\Models\AppProfile;
use App\Models\BillingTemplate;
use App\Models\BillingType;
use App\Models\CustomerData;
use App\Notifications\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BillingController extends Controller
{
    //
    public function invoice2()
    {
        $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'notif')->first();

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

        $replace = [
            uniqid(),
            date('d M Y'),
            date('d M Y'),

            100,
            10,
            110,

            '1 Mbps',
            uniqid(),
            'Dafi',
        ];

        $emailBody = str_replace($search, $replace, $billingTemplate->content);

        $filepath = '/home/kura/SKPRB/inet-billing/storage/app/billing/invoice/2022-05/626aed6a68f15.pdf';

        return (new Invoice($replace, $filepath, $emailBody));
    }

    public function invoice()
    {
        $appProfile = AppProfile::first();

        Carbon::setLocale('id_ID');

        $today = Carbon::today();
        $todayString = $today->toDateString();

        $invoiceDate = $today->endOfMonth()->addDays("-3");
        
        if ($todayString !== $invoiceDate->toDateString()) {
            $invoiceDateFormat = $invoiceDate->format('d F Y');

            $dueDate = $invoiceDate->addDays("+7");
            $dueDateFormat = $dueDate->format('d F Y');

            $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'notif')->first();

            $billingInvoices = BillingType::with(['billing_products'])->whereActive(1)->get();
            foreach ($billingInvoices as $key => $billingInvoice) {
                $invoiceDate = $today->endOfMonth()->addDays("-" . $billingInvoice->notif);
                $invoiceDateFormat = $invoiceDate->format('d M Y');
        
                $dueDate = $invoiceDate->addDays("+" . $billingInvoice->suspend);
                $dueDateFormat = $dueDate->format('d M Y');

                $billingProducts = $billingInvoice->billing_products;
                foreach ($billingProducts as $key => $billingProduct) {

                    $customerDatas = CustomerData::where('active', 1)
                        ->where('product_type_id', $billingProduct->product_type_id)
                        ->where('product_service_id', $billingProduct->product_service_id)
                        ->with([
                            'customer_profiles', 
                            'product_services', 
                            'area_products',
                        ])
                        ->get();
                    foreach ($customerDatas as $key => $customerData) {
                        $customerProfile = $customerData->customer_profiles;
                        $productService = $customerData->product_services;
                        $areaProduct = $customerData->area_products;
                        

                        $billingCode = uniqid();

                        $price_sub = Formatter::rupiah($areaProduct->price_sub);
                        $price_ppn = Formatter::rupiah($areaProduct->price_ppn);

                        $discount = 0;
                        $total = $areaProduct->price_total - $discount;

                        $price_discount = Formatter::rupiah($discount);
                        $price_total = Formatter::rupiah($total);

                        $productName = $productService->name;
                        $customerCode = $customerData->code;
                        $customerName = $customerProfile->name;

                        $terbilang = Formatter::rupiahSpeakOnBahasa((int) $total);

                        $filename = $customerProfile->customer_data->code . '.pdf';
                        $filepath = 'billing/invoice/' . $today->format('Y-m') . '/' . $filename;

                        self::createPDF([
                            'filepath' => $filepath,
            
                            'template_variable' => [
                                'invoice_code' => $billingCode,
                                'invoice_date' => $invoiceDateFormat,
                                'invoice_due' => $dueDateFormat,
                    
                                'company_logo' => $appProfile->logo ? asset('storage/app_profile/' . $appProfile->logo) : asset('logo.jpg'),
                                'company_name' => $appProfile->name,
                                'company_contact' => implode(', ', [$appProfile->email, $appProfile->telp]),
                                'company_address' => $appProfile->address,
                    
                                'cus_code' => $customerCode,
                                'cus_name' => $customerName,
                                'cus_contact' => implode(', ', [$customerProfile->email, $customerProfile->handphone]),
                    
                                'product_service' => $productName,
                                
                                'price_sub' => $price_sub,
                                'price_ppn' => $price_ppn,
                                'price_discount' => $price_discount,
                                'price_total' => $price_total,
                    
                                'sayit' => $terbilang,
                            ],
                        ]);

                        return self::sendEmail(
                            $customerProfile, 
                            $billingTemplate,
                            [
                                $billingCode,
                                $invoiceDateFormat,
                                $dueDateFormat,
        
                                $price_sub,
                                $price_ppn,
                                $price_total,
        
                                $productName,
                                $customerCode,
                                $customerName,
                            ],
                            $filepath,
                        );
                    }

                }
                              
            }
        }
    }

    protected static function createPDF(Array $params)
    {
        $objParam = $params;
        $filepath = $objParam['filepath'];
        $template_variable = $objParam['template_variable'];
    
        $pdf = PDF::loadView('billing.invoice.pdf', $template_variable);
    
        Storage::disk('local')->put($filepath, $pdf->output());
    }

    protected static function sendEmail($customerProfile, $billingTemplate, $replace, $filepath)
    {
        $emailBody = NULL;

        if ($billingTemplate) {
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

        // $customerProfile->notify(new Invoice($replace, $filepath, $emailBody));

        return (new Invoice($replace, $filepath, $emailBody))
            ->toMail($customerProfile);
    }
}
