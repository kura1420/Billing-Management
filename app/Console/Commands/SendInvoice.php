<?php

namespace App\Console\Commands;

use App\Helpers\FileAction;
use App\Helpers\Formatter;
use App\Models\AppProfile;
use App\Models\BillingInvoice;
use App\Models\BillingTemplate;
use App\Models\BillingType;
use App\Models\CustomerData;
use App\Notifications\Invoice;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class SendInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:invoice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification Invoice to Customer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $appProfile = AppProfile::first();

        $today = Carbon::today();
        $todayString = $today->toDateString();
        $dateMonthYear = $today->format('m-Y');

        $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'notif')->first();

        $billingTypes = BillingType::with(['billing_products'])->whereActive(1)->get();

        foreach ($billingTypes as $key => $billingType) {
            $invoiceDate = $today->endOfMonth()->addDays("-" . $billingType->notif);
            $invoiceDateFormat = $invoiceDate->format('d F Y');

            $dueDate = $invoiceDate->addDays("+" . $billingType->suspend);
            $dueDateFormat = $dueDate->format('d F Y');

            $dateFormat = [
                'today' => $today,
                'invoiceDateFormat' => $invoiceDateFormat,
                'dueDate' => $dueDate,
                'dueDateFormat' => $dueDateFormat,
                'dateMonthYear' => $dateMonthYear,
            ];

            if ($billingType->repeat) {
                if (
                    ($todayString >= $invoiceDate->toDateString()) &&
                    ($todayString < $dueDate->toDateString())
                ) {
                    self::process($billingType, $dateFormat, $appProfile, $billingTemplate);
                }
            } else {
                if ($todayString == $invoiceDate->toDateString()) {
                    self::process($billingType, $dateFormat, $appProfile, $billingTemplate);
                }
            }
        }

        return 0;
    }

    protected static function process($billingType, $dateFormat, $appProfile, $billingTemplate)
    {
        $today = $dateFormat['today'];
        $invoiceDateFormat = $dateFormat['invoiceDateFormat'];
        $dueDate = $dateFormat['dueDate'];
        $dueDateFormat = $dateFormat['dueDateFormat'];
        $dateMonthYear = $dateFormat['dateMonthYear'];

        $billingProducts = $billingType->billing_products;
        foreach ($billingProducts as $key => $billingProduct) {
            
            $customerDatas = CustomerData::with(['customer_profiles', 'product_services', 'area_products'])
                ->where('active', 1)
                ->where('product_type_id', $billingProduct->product_type_id)
                ->where('product_service_id', $billingProduct->product_service_id);

            $dateCutOff = Carbon::today()->format('Y-m-') . $billingType->member_end_active;
                    
            if ($billingType->member_end_active > 0) {                        
                $customerDatas->whereRaw('DATE_FORMAT(member_at, "%Y-%m-%d") <= "'. $dateCutOff .'"');
            } 

            $customerDatas->get();

            if (!empty($customerDatas)) {
                foreach ($customerDatas as $key => $customerData) {
                    $customerProfile = $customerData->customer_profiles;
                    $productService = $customerData->product_services;
                    $areaProduct = $customerData->area_products;

                    $billingInvoice = BillingInvoice::where('billing_type_id', $billingType->id)
                        ->where('customer_data_id', $customerData->id)
                        ->where('product_type_id', $billingProduct->product_type_id)
                        ->where('product_service_id', $billingProduct->product_service_id)
                        ->whereRaw('DATE_FORMAT(notif_at, "%m-%Y") = "' . $dateMonthYear . '"');

                    $path = 'billing/invoice/' . $today->format('Y-m') . '/';

                    if ($billingInvoice->count() == 0) {

                        $price_active_after_cutoff = 0;
                        if ($billingType->member_end_active > 0) {
                            $dateEndThisMonth = Carbon::today()->endOfMonth();

                            $customerDataActiveAfterCutOff = CustomerData::where('id', $customerData->id)
                                ->whereRaw('DATE_FORMAT(member_at, "%Y-%m-%d") BETWEEN "' . $dateCutOff . '" AND "' . $dateEndThisMonth->format('Y-m-d') . '"');

                            if ($customerDataActiveAfterCutOff) {
                                $member_at = date('Y-m-d', strtotime($customerDataActiveAfterCutOff->member_at));
                                
                                $dateRangeMemberActiveUntilEndMonths = CarbonPeriod::create($member_at, Carbon::today()->format('Y-m-d'));
                                
                                $loopDateRangeMemberActiveUntilEndMonths = 0;
                                foreach ($dateRangeMemberActiveUntilEndMonths as $dateRangeMemberActiveUntilEndMonth) {
                                    $loopDateRangeMemberActiveUntilEndMonths++;
                                }

                                $priceCutOff = ($loopDateRangeMemberActiveUntilEndMonths/$dateEndThisMonth->format('d')) * 100;
                                $roundPriceCutOff = round($priceCutOff, 2);
                                
                                $price_active_after_cutoff = ($roundPriceCutOff / 100) * $areaProduct->price_sub;
                            }
                        }

                        /**
                         * hitung promo
                         * cari area yg sedang promo
                         * cari customer yang menggunakan area promo
                         * total promo - counting promo
                         */

                        $billingCode = uniqid();

                        $discount = 0;
                        $total = ($areaProduct->price_total + $price_active_after_cutoff) - $discount;

                        $price_sub = Formatter::rupiah($areaProduct->price_sub);
                        $price_ppn = Formatter::rupiah($areaProduct->price_ppn);
                        $price_discount = Formatter::rupiah($discount);
                        $price_total = Formatter::rupiah($total);
                        $price_after_cutoff_format = Formatter::rupiah($price_active_after_cutoff);

                        $productName = $productService->name;
                        $customerCode = $customerData->code;
                        $customerName = $customerProfile->name;

                        $terbilang = Formatter::rupiahSpeakOnBahasa($total);

                        $file_invoice = $customerProfile->customer_data->code . '.pdf';
                        $filepath = $path . $file_invoice;

                        BillingInvoice::create([
                            'code' => $billingCode,
                            'billing_type_id' => $billingType->id,
                            'customer_data_id' => $customerData->id,
                            'notif_at' => $today,
                            'suspend_at' => $dueDate->format('Y-m-d'),
                            'status' => 0,
                            'price_ppn' => $areaProduct->price_ppn,
                            'price_sub' => $areaProduct->price_sub,
                            'price_total' => $total,
                            'price_discount' => $discount,
                            'product_type_id' => $billingProduct->product_type_id,
                            'product_service_id' => $billingProduct->product_service_id,
                            'file_invoice' => $file_invoice,
                        ]);

                        FileAction::createPDF([
                            'filepath' => $filepath,

                            'template_html' => 'billing.invoice.makefile.pdf',
            
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

                                'price_active_after_cutoff' => $price_active_after_cutoff,
                                'price_after_cutoff_format' => $price_after_cutoff_format,
                    
                                'sayit' => $terbilang,
                            ],
                        ]);
                    } else {
                        $customerInvoice = $billingInvoice->first();

                        $billingCode = $customerInvoice->code;
                        $invoiceDateFormat = date('d F Y', strtotime($customerInvoice->notif_at));
                        $dueDateFormat = date('d F Y', strtotime($customerInvoice->suspend_at));
    
                        $price_sub = Formatter::rupiah($customerInvoice->price_sub);
                        $price_ppn = Formatter::rupiah($customerInvoice->price_ppn);
                        $price_total = Formatter::rupiah($customerInvoice->price_total);
    
                        $productName = $productService->name;
                        $customerCode = $customerData->code;
                        $customerName = $customerProfile->name;

                        $filepath = $filepath = $path . $customerInvoice->file_invoice;
                    }

                    self::sendEmail(
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

        $customerProfile->notify(new Invoice($replace, $filepath, $emailBody));
    }
}
