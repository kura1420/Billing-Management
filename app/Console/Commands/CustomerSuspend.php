<?php

namespace App\Console\Commands;

use App\Helpers\Formatter;
use App\Models\AreaRoute;
use App\Models\BillingInvoice;
use App\Models\BillingTemplate;
use App\Models\CustomerData;
use App\Models\CustomerProfile;
use App\Notifications\Suspend;
use Carbon\Carbon;
use RouterOS\{Client, Query, Config};
use RouterOS\Exceptions\ConnectException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CustomerSuspend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customer:suspend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification and Suspend Network Customer';

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

        $billingTemplate = BillingTemplate::where('sender', 'email')->where('type', 'suspend')->first();

        $billingInvoices = BillingInvoice::with(['customer_data', 'product_services', 'billing_types'])
            ->where('status', 0)
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

                self::mikrotik($customerData);

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
                    'router_sites.password',
                    'router_sites.command_trigger_list',
                    'router_sites.command_trigger_comment',
                    'router_sites.command_trigger_terminated',
                ])
                ->get();

            foreach ($areaRouters as $key => $areaRouter) {
                $config = (new Config())
                    ->set('host', $areaRouter->host)
                    ->set('port', (int) $areaRouter->port)
                    ->set('user', $areaRouter->user)
                    ->set('pass', $areaRouter->password);
                    
                $client = new Client($config);

                // $command = '/ip/firewall/address-list/print';
                $query = (new Query($areaRouter->command_trigger_list))
                    ->where('address', $customerData->service_trigger);
                
                $result = $client->query($query)->read();

                if (!empty($result)) {
                    $paramID = $result[0]['.id'];

                    // $command = '/ip/firewall/address-list/set';
                    $query = (new Query($areaRouter->command_trigger_comment))
                        ->equal('.id', $paramID)
                        ->equal('comment', 'Suspend at ' . Carbon::now());
                    $client->query($query);

                    sleep(.1);

                    // $command = '/ip/firewall/address-list/disable';
                    $query = (new Query($areaRouter->command_trigger_terminated))
                        ->equal('.id', $paramID);
                    $client->query($query);

                    break;
                }

                sleep(3);
                unset($client);
            }
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
        }
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

        $customerProfile->notify(new Suspend($replace, $filepath, $emailBody));
    }
}
