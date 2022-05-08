<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\AreaProductPromo;
use App\Models\CustomerData;
use App\Models\ProductPromo;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    //
    public function index()
    {
        $customerDatas = CustomerData::with(['customer_profiles', 'product_services', 'area_products'])->get();        

        /**
         * hitung promo
         * cari area yg sedang promo
         * cari customer yang menggunakan area promo
         * total promo - counting promo
         */

        $data = [];
        foreach ($customerDatas as $customerData) {
            $areaProduct = $customerData->area_products;
            $customerPromo = $customerData->customer_promos()
                ->where('active', 1)
                ->first();

            if ($customerPromo) {
                $productPromo = ProductPromo::where('id', $customerPromo->product_promo_id)
                    ->where('active', 1)
                    ->first();

                $price = $areaProduct->price_sub;
                $total = ($productPromo->discount / 100) * $price;

                $data[] = [
                    'total' => $total,
                    'cus_promo' => $customerPromo,
                    'area_product' => $areaProduct,
                    'product_promo' => $productPromo,
                ];
            }
        }

        return $data;
    }
}
