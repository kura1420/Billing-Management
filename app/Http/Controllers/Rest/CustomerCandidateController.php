<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\CustomerCandidate;
use Illuminate\Http\Request;

class CustomerCandidateController extends Controller
{
    //
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
            'provinsis',
            'cities',
            'product_services',
            'customer_segments',
        ])->find($id);

        $row->provinsi_id = $row->provinsis->name;
        $row->city_id = $row->cities->name;
        $row->product_service_id = $row->product_services->name;
        $row->customer_segment_id = $row->customer_segments ? $row->customer_segments->name : NULL;

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
}
