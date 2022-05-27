<?php

namespace App\Console\Commands;

use App\Helpers\FileAction;
use App\Helpers\Formatter;
use App\Helpers\RestApi;
use App\Models\AppProfile;
use App\Models\Area;
use App\Models\Bank;
use App\Models\BillingInvoice;
use App\Models\BillingTemplate;
use App\Models\BillingType;
use App\Models\CustomerData;
use App\Models\InvoiceTransactionMode;
use App\Models\ProductPromo;
use App\Notifications\Invoice;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CustomerInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:invoice';

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

        $bank = Bank::whereActive(1)->first();

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
                    self::process($billingType, $dateFormat, $appProfile, $billingTemplate, $bank);
                }
            } else {
                if ($todayString == $invoiceDate->toDateString()) {
                    self::process($billingType, $dateFormat, $appProfile, $billingTemplate, $bank);
                }
            }
        }

        return 0;
    }

    protected static function process($billingType, $dateFormat, $appProfile, $billingTemplate, $bank)
    {
        $today = $dateFormat['today'];
        $invoiceDateFormat = $dateFormat['invoiceDateFormat'];
        $dueDate = $dateFormat['dueDate'];
        $dueDateFormat = $dateFormat['dueDateFormat'];
        $dateMonthYear = $dateFormat['dateMonthYear'];

        DB::transaction(function ()
            use ($billingType,$appProfile, $billingTemplate, $bank, $today, $invoiceDateFormat, $dueDate, $dueDateFormat, $dateMonthYear) 
            {
                $billingProducts = $billingType->billing_products;
                foreach ($billingProducts as $key => $billingProduct) {
                    
                    $queryCustomerDatas = CustomerData::with(['customer_profiles', 'product_services', 'area_products',])
                        ->where('active', 1)
                        ->where('product_type_id', $billingProduct->product_type_id)
                        ->where('product_service_id', $billingProduct->product_service_id);

                    $dateCutOff = Carbon::today()->format('Y-m-') . $billingType->member_end_active;
                            
                    if ($billingType->member_end_active > 0) {                        
                        $queryCustomerDatas->whereRaw('DATE_FORMAT(member_at, "%Y-%m-%d") <= "'. $dateCutOff .'"');
                    } 

                    $customerDatas = $queryCustomerDatas->get();

                    if (!empty($customerDatas)) {
                        foreach ($customerDatas as $key => $customerData) {
                            $customerProfile = $customerData->customer_profiles;
                            $productService = $customerData->product_services;
                            $areaProduct = $customerData->area_products;
                            $customerPromo = $customerData->customer_promos()->where('active', 1)->first();

                            $area = Area::join('taxes', 'areas.ppn_tax_id', '=', 'taxes.id')
                                ->where('areas.id', $areaProduct->area_id)
                                ->select('taxes.value')
                                ->first();

                            $billingInvoice = BillingInvoice::where('billing_type_id', $billingType->id)
                                ->where('customer_data_id', $customerData->id)
                                ->where('product_type_id', $billingProduct->product_type_id)
                                ->where('product_service_id', $billingProduct->product_service_id)
                                ->whereRaw('DATE_FORMAT(notif_at, "%m-%Y") = "' . $dateMonthYear . '"');

                            $path = 'billing/invoice/' . $today->format('Y-m') . '/';

                            if ($billingInvoice->count() == 0) {

                                $price_active_after_cutoff = 0;
                                $price_discount = 0;
                                // $new_price = 0;
                                // $ppn = 0;

                                if ($billingType->member_end_active > 0) {
                                    $dateEndThisMonth = Carbon::today()->endOfMonth();

                                    $customerDataActiveAfterCutOff = CustomerData::where('id', $customerData->id)
                                        ->whereRaw('DATE_FORMAT(member_at, "%Y-%m-%d") BETWEEN "' . $dateCutOff . '" AND "' . $dateEndThisMonth->format('Y-m-d') . '"')
                                        ->first();

                                    if (!empty($customerDataActiveAfterCutOff)) {
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

                                if ($customerPromo) {
                                    $productPromo = ProductPromo::where('id', $customerPromo->product_promo_id)->where('active', 1)->first();

                                    $price_discount = ($productPromo->discount / 100) * $areaProduct->price_sub;
                                }

                                $billingCode = uniqid();

                                $total = ($areaProduct->price_total + $price_active_after_cutoff) - $price_discount;

                                $format_price_sub = Formatter::rupiah($areaProduct->price_sub);
                                $format_price_ppn = Formatter::rupiah($areaProduct->price_ppn);
                                $format_price_discount = Formatter::rupiah($price_discount);
                                $format_price_total = Formatter::rupiah($total);
                                $format_price_after_cutoff_format = Formatter::rupiah($price_active_after_cutoff);

                                $productName = $productService->name;
                                $customerCode = $customerData->code;
                                $customerName = $customerProfile->name;

                                $terbilang = Formatter::rupiahSpeakOnBahasa($total);

                                $file_invoice = $customerProfile->customer_data->code . '.pdf';
                                $filepath = $path . $file_invoice;

                                $payment_params = [
                                    'partner_user_id' => $customerData->id,
                                    'bank_code' => $bank->code,
                                    'amount' => $total,
                                    'is_open' => FALSE,
                                    'is_single_use' => TRUE,
                                    // 'expiration_time' => '',
                                    'is_lifetime' => TRUE,
                                    'username_display' => $customerProfile->name,
                                    'email' => $customerProfile->email,
                                    // 'trx_expiration_time' => '',
                                    'partner_trx_id' => $billingCode,
                                    'trx_counter' => 1,
                                ];

                                $payment_endpoint = "/generate-static-va";

                                $payment_method = 'POST';

                                $payment_result = RestApi::run($payment_endpoint, $payment_method, $payment_params);

                                $payment_id = NULL;
                                $va_number = NULL;
                                if ($payment_result) {
                                    $payment_id = $payment_result->id;
                                    $va_number = $payment_result->va_number;
                                }

                                $billingInvoice = BillingInvoice::create([
                                    'code' => $billingCode,
                                    'billing_type_id' => $billingType->id,
                                    'customer_data_id' => $customerData->id,
                                    'notif_at' => $today,
                                    'suspend_at' => $dueDate->format('Y-m-d'),
                                    'status' => 0,
                                    'price_ppn' => $areaProduct->price_ppn,
                                    'price_sub' => $areaProduct->price_sub,
                                    'price_total' => $total,
                                    'price_discount' => $price_discount,
                                    'product_type_id' => $billingProduct->product_type_id,
                                    'product_service_id' => $billingProduct->product_service_id,
                                    'file_invoice' => $file_invoice,
                                ]);

                                InvoiceTransactionMode::create([
                                    'billing_invoice_id' => $billingInvoice->id,
                                    'mode' => 'VA',
                                    'trx_id' => $payment_id,
                                    'trx_payment' => $va_number,
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
                                        
                                        'price_sub' => $format_price_sub,
                                        'price_ppn' => $format_price_ppn,
                                        'price_discount' => $format_price_discount,
                                        'price_total' => $format_price_total,

                                        'price_active_after_cutoff' => $price_active_after_cutoff,
                                        'price_after_cutoff_format' => $format_price_after_cutoff_format,

                                        'va_number' => $va_number,
                            
                                        'sayit' => $terbilang,
                                    ],
                                ]);
                            } else {
                                $customerInvoice = $billingInvoice->first();

                                $billingCode = $customerInvoice->code;
                                $invoiceDateFormat = date('d F Y', strtotime($customerInvoice->notif_at));
                                $dueDateFormat = date('d F Y', strtotime($customerInvoice->suspend_at));
            
                                $format_price_sub = Formatter::rupiah($customerInvoice->price_sub);
                                $format_price_ppn = Formatter::rupiah($customerInvoice->price_ppn);
                                $format_price_total = Formatter::rupiah($customerInvoice->price_total);
            
                                $productName = $productService->name;
                                $customerCode = $customerData->code;
                                $customerName = $customerProfile->name;

                                $filepath = $path . $customerInvoice->file_invoice;
                            }

                            self::sendEmail(
                                $customerProfile, 
                                $billingTemplate,
                                [
                                    $billingCode,
                                    $invoiceDateFormat,
                                    $dueDateFormat,
            
                                    $format_price_sub,
                                    $format_price_ppn,
                                    $format_price_total,
            
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
        );
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
