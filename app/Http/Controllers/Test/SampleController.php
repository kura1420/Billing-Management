<?php

namespace App\Http\Controllers\Test;

use App\Helpers\Formatter;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\AppProfile;
use App\Models\CustomerData;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SampleController extends Controller
{
    //
    public function index()
    {
        $appProfile = AppProfile::first();
        $today = Carbon::today()->format('d M Y');

        $customerData = CustomerData::with(['customer_profiles', 'product_services', 'area_products',])->first();
        $customerProfile = $customerData->customer_profiles;
        $productService = $customerData->product_services;
        $areaProduct = $customerData->area_products;

        $data = [
            'invoice_code' => uniqid(),
            'invoice_date' => $today,
            'invoice_due' => $today,
                            
            'company_logo' => $appProfile->logo ? asset('storage/app_profile/' . $appProfile->logo) : asset('logo.jpg'),
            'company_name' => $appProfile->name,
            'company_contact' => implode(', ', [$appProfile->email, $appProfile->telp]),
            'company_address' => $appProfile->address,
                            
            'cus_code' => $customerData->code,
            'cus_name' => $customerProfile->name,
            'cus_contact' => implode(', ', [$customerProfile->email, $customerProfile->handphone]),
                            
            'product_service' => 'Internet 1 Mbps',

            'price_sub' => Formatter::rupiah(100),
            'price_ppn' => Formatter::rupiah(10),
            'price_discount' => Formatter::rupiah(50),
            'price_total' => Formatter::rupiah(60),

            'price_active_after_cutoff' => 0,
            'price_after_cutoff_format' => Formatter::rupiah(0),

            'va_number' => 123123123,
                            
            'sayit' => Formatter::rupiahSpeakOnBahasa(60),
        ];

        $pdf = PDF::loadView('billing.invoice.makefile.pdf', $data);
        
        return $pdf->stream();
    }
}
