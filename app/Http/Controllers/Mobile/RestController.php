<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerCandidateRequest;
use App\Http\Requests\UserProfileRequest;
use App\Models\AreaProductCustomer;
use App\Models\City;
use App\Models\CustomerCandidate;
use App\Models\CustomerSegment;
use App\Models\CustomerType;
use App\Models\ProductService;
use App\Models\ProductType;
use App\Models\Provinsi;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;

class RestController extends Controller
{
    //
    public function provinsi(Request $request)
    {
        $query = $request->q ?? NULL;

        $table = Provinsi::select('id', 'name');

        if ($query) {
            $table->where('name', 'like', "%$query%");
        }

        $rows = $table->orderBy('name', 'asc')
            ->get();

        return response()->json($rows);
    }

    public function city(Request $request)
    {
        $provinsi = $request->provinsi_id ?? NULL;
        $query = $request->q ?? NULL;
    
        $table = City::select('id', 'name')
            ->where('provinsi_id', $provinsi);

        if ($query) {
            $table->where('name', 'like', "%$query%");
        }

        $rows = $table->orderBy('name', 'asc')
            ->get();
        
        return response()->json($rows);
    }

    public function segments(Request $request)
    {
        $provinsi = $request->provinsi ?? NULL;
        $city = $request->city ?? NULL;

        $rows = AreaProductCustomer::where('provinsi_id', $provinsi)
            ->where('city_id', $city)
            ->where('active', 1)
            ->distinct()
            ->get()
            ->map(fn($row) => [
                'id' => $row->customer_segment_id,
                'customer_type_name' => CustomerType::where('id', $row->customer_type_id)->first()->name,
                'customer_segment_name' => CustomerSegment::where('id', $row->customer_segment_id)->first()->name,
            ]);

        return response()->json($rows);
    }

    public function products(Request $request)
    {
        $provinsi = $request->provinsi ?? NULL;
        $city = $request->city ?? NULL;
        $segment = $request->segment ?? NULL;

        $rows = AreaProductCustomer::where('provinsi_id', $provinsi)
            ->where('city_id', $city)
            ->where('customer_segment_id', $segment)
            ->where('active', 1)
            ->distinct()
            ->get()
            ->map(fn($row) => [
                'id' => $row->id,
                'product_type_name' => ProductType::where('id', $row->product_type_id)->first()->name,
                'product_service_name' => ProductService::where('id', $row->product_service_id)->first()->name,
            ]);

        return response()->json($rows);
    }

    public function customerCandidate(CustomerCandidateRequest $request)
    {
        $file = uniqid() . '.' . $request->file->extension();

        $request->file('file')->storeAs(
            'customer_candidate',
            $file,
            'local'
        );

        $areaProductCustomer = AreaProductCustomer::where('id', $request->product_service_id)->first();

        CustomerCandidate::create([
            'fullname' => $request->fullname,
            'email' => $request->email,
            'handphone' => $request->handphone,
            'file' => $file,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            // 'status' => $request->status,
            'from' => $request->from,
            'signature' => $request->signature,
            'user_id' => $request->user_id,
            'area_id' => $areaProductCustomer->area_id,
            'provinsi_id' => $request->provinsi_id,
            'city_id' => $request->city_id,
            'product_type_id' => $areaProductCustomer->product_type_id,
            'product_service_id' => $areaProductCustomer->product_service_id,
            'area_product_id' => $areaProductCustomer->id,
            // 'area_product_promo_id' => $request->area_product_promo_id,
        ]);

        return response()->json('OK', 201);
    }

    public function profile()
    {
        $id = session()->get('user_data')['id'];
        
        $userProfile = UserProfile::with('departements')->where('user_id', $id)->first();

        $result = [
            'total' => 1,
            'rows' => [
                [
                    'name' => 'Fullname',
                    'value' => $userProfile->fullname,
                    'group' => 'Profile',
                    'editor' => 'textbox'
                ],
                [
                    'name' => 'Email',
                    'value' => $userProfile->email,
                    'group' => 'Profile',
                    'editor' => [
                        'type' => 'textbox',
                        'options' => [
                            'validType' => 'email',
                        ],
                    ],
                ],
                [
                    'name' => 'Telp',
                    'value' => $userProfile->telp,
                    'group' => 'Profile',
                    'editor' => 'numberbox'
                ],
                [
                    'name' => 'Handphone',
                    'value' => $userProfile->handphone,
                    'group' => 'Profile',
                    'editor' => 'numberbox'
                ],
                [
                    'name' => 'Departement',
                    'value' => $userProfile->departements->name,
                    'group' => 'Profile',
                ],
                [
                    'name' => 'Password',
                    'value' => '',
                    'group' => 'Profile',
                    'editor' => [
                        'type' => 'passwordbox',
                        'options' => [
                            'showEye' => true
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($result, 200);
    }

    public function updateProfile(UserProfileRequest $request)
    {
        $id = session()->get('user_data')['id'];

        $user = User::find($id);
        $userProfile = $user->user_profiles()->first();

        $userProfile->update([
            'fullname' => $request->fullname,
            'email' => strtolower($request->email),
            'telp' => $request->telp,
            'handphone' => $request->handphone,
        ]);

        $userField = [
            'name' => $request->fullname,
            'email' => strtolower($request->email),
        ];

        if ($request->password) {
            $userField['password'] = bcrypt($request->password);
        }

        $user->update($userField);

        return response()->json('OK', 200);
    }

    public function logout()
    {
        session()->flush();

        return response()->json('OK', 200);
    }
}
