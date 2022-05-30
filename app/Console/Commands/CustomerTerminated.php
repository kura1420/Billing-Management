<?php

namespace App\Console\Commands;

use App\Models\BillingInvoice;
use App\Models\BillingTemplate;
use App\Models\CustomerData;
use App\Models\CustomerProfile;
use App\Notifications\Terminate;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CustomerTerminated extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:terminated';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification and Terminated Network Customer';

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

        $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'terminated')->first();

        $billingInvoices = BillingInvoice::with(['customer_data', 'product_services'])
            ->where('status', 2)
            ->whereRaw('DATE_FORMAT(terminate_at, "%m-%Y")="'.$dateMonthYear.'"')
            ->get();

        foreach ($billingInvoices as $key => $billingInvoice) {
            if ($todayString !== date('Y-m-d', strtotime($billingInvoice->terminate_at))) {
                $customerData = $billingInvoice->customer_data;
                $productService = $billingInvoice->product_services;

                $customerProfile = CustomerProfile::where('customer_data_id', $customerData->id)->first();

                $replace = [
                    $billingInvoice->code,
                    $billingInvoice->notif_at,
                    $billingInvoice->terminate_at,
                
                    $billingInvoice->price_sub,
                    $billingInvoice->price_ppn,
                    $billingInvoice->price_total,
                
                    $productService->name,
                    $customerData->code,
                    $customerProfile->name,
                ];

                self::sendEmail($customerProfile, $billingTemplate, $replace);

                CustomerData::where('id', $customerData->id)->update([
                    'active' => 0,
                    'terminate_at' => $today,
                ]);

                $billingInvoice->update([
                    'status' => 4,
                    'terminate_at' => $today,
                ]);
            }
        }

        return 0;
    }

    protected static function sendEmail($customerProfile, $billingTemplate, $replace) 
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

        $customerProfile->notify(new Terminate($replace, $emailBody));
    }
}
