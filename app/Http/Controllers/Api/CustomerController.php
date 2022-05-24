<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerData;
use App\Models\CustomerSegment;
use App\Models\CustomerType;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    protected $folder_picture = 'customer_profile';
    protected $folder_file = 'customer_file';

    public function type(Request $request)
    {
        $query = $request->q ?? NULL;

        $table = CustomerType::select('id', 'code', 'name', 'desc', 'active');

        if ($query) {
            $table->where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->orWhere('desc', 'like', "%{$query}%");
        }

        $rows = $table->orderBy('name', 'asc')
            ->get();

        return response()->json($rows);
    }

    public function segment(Request $request)
    {
        $query = $request->q ?? NULL;

        $table = CustomerSegment::select('id', 'code', 'name', 'desc', 'active');

        if ($query) {
            $table->where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->orWhere('desc', 'like', "%{$query}%");
        }

        $rows = $table->orderBy('name', 'asc')
            ->get();

        return response()->json($rows);
    }

    public function show($code)
    {
        $result = new \stdClass;

        $row = CustomerData::where('code', $code)
            ->first();

        $customerProfile = $row->customer_profiles()->first();
        $customerPromo = $row->customer_promos()->first();
        $customerType = $row->customer_types()->first();
        $customerSegment = $row->customer_segments()->first();
        $area = $row->areas()->first();
        $provinsi = $row->provinsis()->first();
        $city = $row->cities()->first();
        $productType = $row->product_types()->first();
        $productService = $row->product_services()->first();

        $row->member_at = date('m/d/Y', strtotime($row->member_at));
        $row->suspend_at = $row->suspend_at ? date('m/d/Y', strtotime($row->suspend_at)) : '';
        $row->terminate_at = $row->terminate_at ? date('m/d/Y', strtotime($row->terminate_at)) : '';
        $row->dismantle_at = $row->dismantle_at ? date('m/d/Y', strtotime($row->dismantle_at)) : '';

        $row->customer_type = $customerType ? $customerType->name : NULL;
        $row->customer_segment = $customerSegment ? $customerSegment->name : NULL;
        $row->area = $area ? $area->name : NULL;
        $row->provinsi = $provinsi ? $provinsi->name : NULL;
        $row->city = $city ? $city->name : NULL;
        $row->product_type = $productType ? $productType->name : NULL;
        $row->product_service = $productService ? $productService->name : NULL;

        if ($customerPromo) {
            $row->area_product_promo = $customerPromo->area_product_promo_id;
        }

        if ($customerProfile) {
            $row->name = $customerProfile->name;
            $row->gender = $customerProfile->gender;
            $row->email = $customerProfile->email;
            $row->telp = $customerProfile->telp;
            $row->handphone = $customerProfile->handphone;
            $row->fax = $customerProfile->fax;
            $row->address = $customerProfile->address;
            $row->picture = $customerProfile->picture;
            $row->birthdate = $customerProfile->birthdate ? date('m/d/Y', strtotime($customerProfile->birthdate)) : '';
            $row->marital_status = $customerProfile->marital_status;
            $row->work_type = $customerProfile->work_type;
            $row->child = $customerProfile->child;

            if ($row->picture) {
                $row->picture_url = asset('storage/' . $this->folder_picture . '/' . $row->picture);
            }
        }

        unset($row->id);
        unset($row->customer_type_id);
        unset($row->customer_segment_id);
        unset($row->area_id);
        unset($row->provinsi_id);
        unset($row->city_id);
        unset($row->area_product_id);
        unset($row->area_product_customer_id);
        unset($row->product_type_id);
        unset($row->product_service_id);
        unset($row->created_at);
        unset($row->updated_at);
        unset($row->picture);
        
        return response()->json($row);
    }
}
