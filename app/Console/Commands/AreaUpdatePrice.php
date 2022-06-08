<?php

namespace App\Console\Commands;

use App\Models\ScheduleUpdatePrice;
use App\Notifications\UpdatePrice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AreaUpdatePrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'area:updateprice';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule Area Update Price';

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

        $areaUpdatePrices = ScheduleUpdatePrice::with('areas')->whereStatus(0)->get();

        foreach ($areaUpdatePrices as $areaUpdatePrice) {
            $start_from = $areaUpdatePrice->start_from;

            switch ($start_from) {
                case 'malam_ini':
                    self::updatePriceProduct($areaUpdatePrice);

                    $areaUpdatePrice->update([
                        'status' => 1
                    ]);
                    break;

                case 'awal_bulan':
                    $convertToDay = date('d', strtotime($today));
                    if ($convertToDay == 1) {
                        self::updatePriceProduct($areaUpdatePrice);   
                        
                        $areaUpdatePrice->update([
                            'status' => 1
                        ]);              
                    }
                    break;

                case 'akhir_bulan':
                    $convertToEndDay = Carbon::parse($today)->endOfMonth()->toDateString();

                    if ($convertToEndDay == $today) {
                        self::updatePriceProduct($areaUpdatePrice);
                        
                        $areaUpdatePrice->update([
                            'status' => 1
                        ]);
                    }
                    break;
                
                default:
                    Log::info([
                        'datetime' => Carbon::now(),
                        'class' => 'App\Console\Commands\AreaUpdatePrice',
                        'variable' => $start_from,
                        'message' => 'No defined',
                    ]);
                    break;
            }
        }

        return 0;
    }

    protected static function updatePriceProduct($areaUpdatePrice)
    {
        DB::transaction(function() use ($areaUpdatePrice) {
            $areas = $areaUpdatePrice->areas;
            $ppn_tax = \App\Models\Tax::find($areas->ppn_tax_id)->first();

            $areaProducts = $areas->area_products()->where('active', 1)->get();

            foreach ($areaProducts as $areaProduct) {
                $customerDatas = $areaProduct->customer_data()->where('active', 1)->get();
                $productService = \App\Models\ProductService::where('id', $areaProduct->product_service_id)->first();

                if ($areaProduct->price_sub !== $productService->price) {
                    $sub = $productService->price;
                    $ppn = ($ppn_tax->value / 100) * $sub;
                    $total = $sub + $ppn;

                    $fields = [
                        'price_sub' => $sub,
                        'price_ppn' => $ppn,
                        'price_total' => $total,
                    ];

                    $areaProduct->update($fields);

                    foreach ($customerDatas as $customerData) {
                        $customerProfile = $customerData->customer_profiles()->first();

                        $customerProfile->notify(new UpdatePrice($fields));
                    }
                }
            }
        });        
    }
}
