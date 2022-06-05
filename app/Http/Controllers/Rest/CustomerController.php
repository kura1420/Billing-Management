<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerDocumentRequest;
use App\Http\Requests\CustomerRequest;
use App\Models\CustomerContact;
use App\Models\CustomerData;
use App\Models\CustomerDocument;
use App\Models\CustomerProfile;
use App\Models\CustomerPromo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    //
    protected $folder_picture = 'customer_profile';
    protected $folder_file = 'customer_file';

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = CustomerData::join('customer_profiles', 'customer_data.id', '=', 'customer_profiles.customer_data_id')
            ->join('areas', '.area_id', '=', 'areas.id')
            ->join('product_types', '.product_type_id', '=', 'product_types.id')
            ->join('product_services', '.product_service_id', '=', 'product_services.id')
            ->select([
                'customer_data.id',
                'customer_data.code',
                'customer_data.active',
                'customer_data.member_at',
                'customer_data.suspend_at',
                'customer_data.terminate_at',
                'customer_data.dismantle_at',
                'customer_data.created_at',
                'customer_data.updated_at',
                    'customer_profiles.name',
                    'customer_profiles.email',
                    'customer_profiles.handphone',
                        'areas.name as area_id',
                            'product_types.name as product_type_id',
                                'product_services.name as product_service_id',
            ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('customer_data.code', 'like', "%{$search}%")
                ->orWhere('customer_profiles.name', 'like', "%{$search}%")
                ->orWhere('customer_profiles.email', 'like', "%{$search}%")
                ->orWhere('customer_profiles.handphone', 'like', "%{$search}%")
                ->orWhere('areas.name as area_id', 'like', "%{$search}%")
                ->orWhere('product_types.name as product_type_id', 'like', "%{$search}%")
                ->orWhere('product_services.name as product_service_id', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(CustomerRequest $request)
    {
        $contacts = json_decode($request->contacts, TRUE);
        $area_product_promo_id = $request->area_product_promo_id ?? NULL;

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
                'service_trigger' => $request->service_trigger,
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

        $customerProfile = CustomerProfile::updateOrCreate(
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

        if (!empty($contacts)) {
            foreach ($contacts as $key => $value) {
                $contactId = empty($value['id']) ? NULL: $value['id'];

                CustomerContact::updateOrCreate(
                    [
                        'id' => $contactId,
                    ],
                    [
                        'customer_profile_id' => $customerProfile->id,
                        'customer_data_id' => $customerData->id,
                        'name' => $value['name'],
                        'gender' => $value['gender'],
                        'telp' => $value['telp'],
                        'handphone' => $value['handphone'],
                        'email' => $value['email'],
                        'address' => $value['address'],
                    ]
                );
            }
        }

        if ($area_product_promo_id) {
            $areaProductPromo = \App\Models\AreaProductPromo::with('product_promos')->where('id', $area_product_promo_id)->first();
            $productPromo = $areaProductPromo->product_promos;

            CustomerPromo::updateOrCreate(
                [
                    'customer_data_id' => $customerData->id,
                    'area_product_promo_id' => $area_product_promo_id,
                ],
                [
                    'product_promo_id' => $areaProductPromo->product_promo_id,
                    'until_payment' => $productPromo->until_payment,
                    'invoice_counting' => 0,
                    'active' => 1,
                ]
            );
        }

        $status = $request->id ? 200 : 201;

        return response()->json($customerData, $status);
    }

    public function show($id)
    {
        $row = CustomerData::find($id);
        $customerProfile = $row->customer_profiles()->first();
        $customerPromo = $row->customer_promos()->first();

        $row->member_at = date('m/d/Y', strtotime($row->member_at));
        $row->suspend_at = $row->suspend_at ? date('m/d/Y', strtotime($row->suspend_at)) : '';
        $row->terminate_at = $row->terminate_at ? date('m/d/Y', strtotime($row->terminate_at)) : '';
        $row->dismantle_at = $row->dismantle_at ? date('m/d/Y', strtotime($row->dismantle_at)) : '';
        $row->area_product_customer = $row->area_product_customer_id;

        if ($customerPromo) {
            $row->area_product_promo_id = $customerPromo->area_product_promo_id;
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

    public function contactLists($id)
    {
        $rows = CustomerContact::where('customer_data_id', $id)->orderBy('name', 'asc')->get();

        return response()->json($rows);
    }

    public function contactDestroy($id)
    {
        CustomerContact::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function contactMerge($id)
    {
        $profile = CustomerProfile::where('customer_data_id', $id)->first()->toArray();
        $contacts = CustomerContact::where('customer_data_id', $id)->get()->toArray();
        
        array_push($contacts, $profile);

        return response()->json($contacts);
    }

    public function documentLists($id)
    {
        $rows = CustomerDocument::where('customer_data_id', $id)->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($row) {
                $person = CustomerProfile::where('id', $row->customer_contact_id)->first();
                
                if (! $person) {
                    $person = CustomerContact::where('id', $row->customer_contact_id)->first();
                }

                return [
                    'id' => $row->id,
                    'type' => $row->type,
                    'file' => $row->file,
                    'identity_number' => $row->identity_number,
                    'identity_expired' => $row->identity_expired ? date('d/M/Y', strtotime($row->identity_expired)) : '',
                    'customer_data_id' => $row->customer_data_id,
                    'customer_contact_id' => $row->customer_contact_id,
                    'customer_contact_name' => $person->name,
                ];
            });

        return response()->json($rows);
    }

    public function documentShow($id)
    {
        $row = CustomerDocument::find($id);        
        $row->identity_expired = $row->identity_expired ? date('m/d/Y', strtotime($row->identity_expired)) : '';
        $row->file_url = url('/rest/customer/document-file/' . $row->id);

        return response()->json($row);
    }

    public function documentStore(CustomerDocumentRequest $request)
    {
        $row = CustomerDocument::where('id', $request->d_id)->first();

        $file = $row->file ?? NULL;
        if ($request->d_file) {
            $file = uniqid() . '.' . $request->d_file->extension();

            $request->file('d_file')->storeAs(
                $this->folder_file,
                $file,
                'local'
            );
        }

        CustomerDocument::updateOrCreate(
            [
                'customer_contact_id' => $request->d_customer_contact_id,
            ],
            [
                'type' => $request->d_type,
                'file' => $file,
                'identity_number' => $request->d_identity_number,
                'identity_expired' => $request->d_identity_expired ? date('Y-m-d', strtotime($request->d_identity_expired)) : NULL,
                'customer_data_id' => $request->customer_data_id,
            ]
        );

        $status = $request->customer_contact_id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function documentDestroy($id)
    {
        CustomerDocument::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function documentFile($id)
    {
        $row = CustomerDocument::find($id);

        if ($row) {
            $path = Storage::path($this->folder_file . '/' . $row->file);
    
            return response()->file($path);
        } else {
            return abort(404);
        }
    }
}
