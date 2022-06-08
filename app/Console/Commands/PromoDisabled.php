<?php

namespace App\Console\Commands;

use App\Models\ProductPromo;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PromoDisabled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promo:disabled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disabled product promo automatic by date';

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

        $productPromos = ProductPromo::where('active', 1)->get();

        foreach ($productPromos as $productPromo) {
            if ($today > $productPromo->end) {
                $productPromo->update([
                    'active' => 0,
                ]);
            }
        }

        return 0;
    }
}
