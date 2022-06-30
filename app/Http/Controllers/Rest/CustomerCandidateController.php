<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\CustomerCandidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerCandidateController extends Controller
{
    //
    protected $folder_file = 'customer_candidate';

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = CustomerCandidate::join('provinsis', 'customer_candidates.provinsi_id', '=', 'provinsis.id')
            ->join('cities', 'customer_candidates.city_id', '=', 'cities.id')
            ->join('product_services', 'customer_candidates.product_service_id', '=', 'product_services.id')
            ->leftJoin('customer_segments', 'customer_candidates.customer_segment_id', '=', 'customer_segments.id')
            ->select([
                'customer_candidates.id',
                'customer_candidates.fullname',
                'customer_candidates.email',
                'customer_candidates.handphone',
                'customer_candidates.longitude',
                'customer_candidates.latitude',
                'customer_candidates.status',
                'customer_candidates.area_product_id',
                    'provinsis.name as provinsi_name',
                        'cities.name as city_name',
                            'product_services.name as product_service_name',
                                'customer_segments.name as customer_segment_name',
            ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('fullname', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('handphone', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }
    
    public function store(Request $request)
    {
        // CustomerCandidate::updateOrCreate(
        //     [
        //         'id' => $request->id,
        //     ],
        //     [
        //         'code' => strtoupper($request->code),
        //         'name' => $request->name,
        //         'desc' => $request->desc,
        //         'active' => $request->active == 'true' ? 1 : 0,
        //     ]
        // );

        // $status = $request->id ? 200 : 201;

        // return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = CustomerCandidate::with([
            'users',
            'provinsis',
            'cities',
            'product_services',
            'customer_segments',
        ])->find($id);

        $row->provinsi_id = $row->provinsis->name;
        $row->city_id = $row->cities->name;
        $row->product_service_id = $row->product_services->name;
        $row->customer_segment_id = $row->customer_segments ? $row->customer_segments->name : NULL;
        $row->user_id = $row->users ? $row->users->name : NULL;
        $row->file_url = url('rest/customer-candidate/view-file/' . $row->id . '/file');
        $row->signature_url = url('rest/customer-candidate/view-file/' . $row->id . '/signature');

        return $row;
    }
    
    public function destroy($id)
    {
        CustomerCandidate::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = CustomerCandidate::orderBy('fullname')->get();

        return response()->json($rows);
    }

    public function viewFile($id, $field)
    {
        $row = CustomerCandidate::find($id);

        if ($row) {
            if ($this->check_base64_image($row->{$field})) {
                if (!empty($row->{$field})) {
                    $image = imagecreatefromstring(base64_decode($row->{$field}));
                    
                    return response()->file($image);
                } else {
                    return abort(404);
                }
            } else {
                $path = Storage::path($this->folder_file . '/' . $row->{$field});
        
                return response()->file($path);
            }
        } else {
            return abort(404);
        }
    }

    protected function check_base64_image($base64) {
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $base64)) {
           return TRUE;
        } else {
           return FALSE;
        }
    }
}
