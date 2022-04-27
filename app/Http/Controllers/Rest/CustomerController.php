<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Models\CustomerData;
use App\Models\CustomerProfile;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    protected $folder_picture = 'customer_profile';

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = CustomerData::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('code', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(Request $request)
    {
        $customerData = CustomerData::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                // 'code' => strtoupper($request->code),
                'code' => uniqid(),
                'active' => $request->active == 'true' || $request->active == 1 ? 1 : 0,
                'member_at' => $request->member_at ? date('Y-m-d', strtotime($request->member_at)) : NULL,
                // 'suspend_at' => $request->suspend_at ? date('Y-m-d-', strtotime($request->suspend_at)) : NULL,
                // 'terminate_at' => $request->terminate_at ? date('Y-m-d', strtotime($request->terminate_at)) : NULL,
                // 'dismantle_at' => $request->dismantle_at ? date('Y-m-d', strtotime($request->dismantle_at)) : NULL,
                'customer_type_id' => $request->customer_type_id,
                'customer_segment_id' => $request->customer_segment_id,
                'area_id' => $request->area_id,
                'provinsi_id' => $request->provinsi_id,
                'city_id' => $request->city_id,
                'area_product_id' => $request->area_product_id,
                'area_product_customer_id' => $request->area_product_customer_id,
                'product_type_id' => $request->product_type_id,
                'product_service_id' => $request->product_service_id,
            ]
        );

        $customerProfile = CustomerProfile::where('customer_data_id', $customerData->id)->first();

        $picture = $customerProfile->picture ?? NULL;
        if ($request->picture) {
            $picture = uniqid() . '.' . $request->picture->extension();

            $request->file('picture')->storeAs(
                $this->folder_picture,
                $picture,
                'public',
            );
        }

        CustomerProfile::updateOrCreate(
            [
                'customer_data_id' => $customerData->id,
            ],
            [
                'name' => $request->name,
                'gender' => strtolower($request->gender),
                'email' => strtolower($request->email),
                'telp' => $request->telp,
                'handphone' => $request->handphone,
                'fax' => $request->fax,
                'address' => $request->address,
                'picture' => $picture,
                'birthdate' => $request->birthdate ? date('Y-m-d', strtotime($request->birthdate)) : NULL,
                'marital_status' => $request->marital_status,
                'work_type' => $request->work_type,
                'child' => $request->child ?? 0,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json($customerData, $status);
    }

    public function show($id)
    {
        $row = CustomerData::find($id);
        $customerProfile = $row->customer_profiles()->first();

        $row->area_product_customer = $row->area_product_customer_id;
        $row->active = $row->active == 1 ? 'on' : 'off';

        if ($customerProfile) {
            $row->name = $customerProfile->name;
            $row->gender = $customerProfile->gender;
            $row->email = $customerProfile->email;
            $row->telp = $customerProfile->telp;
            $row->handphone = $customerProfile->handphone;
            $row->fax = $customerProfile->fax;
            $row->address = $customerProfile->address;
            $row->picture = $customerProfile->picture;
            $row->birthdate = $customerProfile->birthdate;
            $row->marital_status = $customerProfile->marital_status;
            $row->work_type = $customerProfile->work_type;
            $row->child = $customerProfile->child;

            if ($row->picture) {
                $row->picture_url = asset('storage/' . $this->folder_picture . '/' . $row->picture);
            }
        }

        return $row;
    }
    
    public function destroy($id)
    {
        CustomerData::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = CustomerData::where('active', 1)->orderBy('name')->get();

        return response()->json($rows);
    }
}
