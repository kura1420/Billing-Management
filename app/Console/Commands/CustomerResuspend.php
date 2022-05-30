<?php

namespace App\Console\Commands;

use App\Helpers\Formatter;
use App\Models\BillingInvoice;
use App\Models\BillingTemplate;
use App\Models\CustomerData;
use App\Models\CustomerProfile;
use App\Notifications\Resuspend;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CustomerResuspend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:resuspend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification and Resuspend Network Customer';

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
        $today = Carbon::today();
        $todayString = $today->toDateString();
        $dateMonthYear = $today->format('m-Y');

        $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'resuspend')->first();

        $billingInvoices = BillingInvoice::with(['customer_data', 'product_services', 'billing_types'])
            ->where('status', 3)
            ->whereRaw('DATE_FORMAT(suspend_at, "%m-%Y")="'.$dateMonthYear.'"')
            ->get();

        foreach ($billingInvoices as $key => $billingInvoice) {
            if ($todayString !== date('Y-m-d', strtotime($billingInvoice->suspend_at))) {
                $customerData = $billingInvoice->customer_data;
                $productService = $billingInvoice->product_services;
                $billingType = $billingInvoice->billing_types;

                $customerProfile = CustomerProfile::where('customer_data_id', $customerData->id)->first();

                $terminateDate = $today->addDay("+" . $billingType->terminated);

                $filepath = 'billing/invoice/' . date('Y-m', strtotime($billingInvoice->notif_at)) . '/' . $billingInvoice->file_invoice;

                $replace = [
                    $billingInvoice->code,
                    $today,
                    $terminateDate,
                
                    Formatter::rupiah($billingInvoice->price_sub),
                    Formatter::rupiah($billingInvoice->price_ppn),
                    Formatter::rupiah($billingInvoice->price_total),
                
                    $productService->name,
                    $customerData->code,
                    $customerProfile->name,
                ];

                self::sendEmail($customerProfile, $billingTemplate, $replace, $filepath);

                CustomerData::where('id', $customerData->id)->update([
                    // 'status' => 0,
                    'suspend_at' => $today,
                ]);

                $billingInvoice->update([
                    'status' => 2,
                    'suspend_at' => $today,
                    'terminate_at' => $terminateDate,
                ]);
            }
        }
        
        return 0;
    }

    protected static function sendEmail($customerProfile, $billingTemplate, $replace, $filepath) 
    {
        $emailBody = NULL;

        if ($billingTemplate) {
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

        $customerProfile->notify(new Resuspend($replace, $emailBody));
    }
}
