<?php

namespace App\Console\Commands;

use App\Models\AreaRoute;
use App\Models\BillingInvoice;
use App\Models\BillingTemplate;
use App\Models\CustomerData;
use App\Models\CustomerProfile;
use App\Notifications\Terminate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use RouterOS\{Client, Query, Config};
use RouterOS\Exceptions\ConnectException;

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

                self::mikrotik($customerData);

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

    protected static function mikrotik($customerData)
    {
        try {
            $areaRouters = AreaRoute::join('router_sites', 'area_routes.router_site_id', '=', 'router_sites.id')
                ->where('area_routes.area_id', $customerData->area_id)
                ->select([
                    'router_sites.site',
                    'router_sites.host',
                    'router_sites.port',
                    'router_sites.user',
                    'router_sites.password'
                ])
                ->get();

            foreach ($areaRouters as $key => $value) {
                $config = (new Config())
                    ->set('host', $value->host)
                    ->set('port', (int) $value->port)
                    ->set('user', $value->user)
                    ->set('pass', $value->password);
                    
                $client = new Client($config);

                $command = '/ip/firewall/address-list/print';
                $query = (new Query($command))
                    ->where('address', $customerData->service_trigger);
                
                $result = $client->query($query)->read();

                if (!empty($result)) {
                    $paramID = $result[0]['.id'];

                    $command = '/ip/firewall/address-list/set';
                    $query = (new Query($command))
                        ->equal('.id', $paramID)
                        ->equal('comment', 'Resuspend at ' . Carbon::now());

                    sleep(.1);

                    $command = '/ip/firewall/address-list/disable';
                    $query = (new Query($command))
                        ->equal('.id', $paramID);

                    break;
                }

                sleep(3);
                unset($client);
            }
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
        }
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
