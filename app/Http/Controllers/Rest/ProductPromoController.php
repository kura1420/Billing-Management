<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductPromoRequest;
use App\Models\AreaProductPromo;
use App\Models\ProductPromo;
use App\Models\ProductPromoService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductPromoController extends Controller
{
    //
    protected $folder_image = 'product_promo';

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = ProductPromo::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }
    
    public function store(ProductPromoRequest $request)
    {
        $areas = json_decode($request->areas, TRUE);

        $row = ProductPromo::find($request->id);

        $image = $row->image ?? NULL;
        if ($request->image) {
            $image = Str::random(10) . '.' . $request->image->extension();

            $request->file('image')->storeAs(
                $this->folder_image,
                $image,
                'public'
            );
        }

        $productPromo = ProductPromo::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'desc' => $request->desc,
                'active' => $request->active == 'true' ? 1 : 0,
                'start' => date('Y-m-d', strtotime($request->start)),
                'end' => date('Y-m-d', strtotime($request->end)),
                'image' => $image,
                'type' => $request->type,
                'discount' => $request->discount,
                'until_payment' => $request->until_payment,
            ]
        );

        ProductPromoService::updateOrCreate(
            [
                'product_promo_id' => $productPromo->id,
            ],
            [
                'product_type_id' => $request->product_type_id,
                'product_service_id' => $request->product_service_id,
            ]
        );

        if (!empty($areas)>0) {
            foreach ($areas as $key => $value) {
                AreaProductPromo::updateOrCreate(
                    [
                        // 'area_product_id' => $value['area_product_id'],
                        'area_id' => $value['area_id'],
                        'product_promo_id' => $productPromo->id,
                    ],
                    [
                        'active' => $value['active'] == 'Yes' ? 1 : 0,
                        'product_type_id' => $request->product_type_id,
                        'product_service_id' => $request->product_service_id,
                    ]
                );
            }
        }

        $status = $request->id ? 200 : 201;

        return response()->json($productPromo, $status);
    }

    public function show($id)
    {
        $row = ProductPromo::find($id);
        $row->start = date('m/d/Y', strtotime($row->start));
        $row->end = date('m/d/Y', strtotime($row->end));
        
        $product_service = $row->product_promo_services()->first();
        
        $row->product_type_id = $product_service->product_type_id;
        $row->product_service_id = $product_service->product_service_id;

        if ($row->image) {
            $row->image_url = asset('storage/' . $this->folder_image . '/' . $row->image);
        }

        return $row;
    }
    
    public function destroy($id)
    {
        ProductPromoService::where('product_promo_id', $id)->delete();

        ProductPromo::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = ProductPromo::with('product_promo_services')->where('active', 1)->orderBy('name')->get();

        return response()->json($rows);
    }

    public function areaLists($id)
    {
        $rows = AreaProductPromo::where('product_promo_id', $id)
            ->get()
            ->map(function ($row) {
                return [
                    'id' => $row->id,
                    'area_id' => $row->area_id,
                    'name' => \App\Models\Area::where('id', $row->area_id)->first()->name,
                    'active' => $row->active == 1 ? 'Yes' : 'No',
                ];
            });

        return response()->json($rows);
    }

    public function areaDestroy($id)
    {
        AreaProductPromo::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function areaFilter(Request $request)
    {
        $area_id = $request->area_id;
        $product_type_id = $request->product_type_id;
        $product_service_id = $request->product_service_id;

        $rows = [];

        $areaTypeServices = AreaProductPromo::where([
            ['area_id', '=', $area_id],
            ['active', '=', 1],
            ['product_type_id', '=', $product_type_id],
            ['product_service_id', '=', $product_service_id],
        ])->get();

        foreach ($areaTypeServices as $areaTypeService) {
            $rows[] = [
                'id' => $areaTypeService->id,
                'name' => \App\Models\ProductPromo::where('id', $areaTypeService->product_promo_id)->first()->name,
            ];
        }

        $areaTypes = AreaProductPromo::where('area_id', '=', $area_id)
        ->where('active', 1)
        ->whereNull('product_type_id')
        ->get();

        foreach ($areaTypes as $areaType) {
            $rows[] = [
                'id' => $areaType->id,
                'name' => \App\Models\ProductPromo::where('id', $areaType->product_promo_id)->first()->name,
            ];
        }

        $areas = AreaProductPromo::where('area_id', '=', $area_id)
        ->where('active', 1)
        ->whereNull('product_type_id')
        ->whereNull('product_service_id')
        ->get();

        foreach ($areas as $area) {
            $rows[] = [
                'id' => $area->id,
                'name' => \App\Models\ProductPromo::where('id', $area->product_promo_id)->first()->name,
            ];
        }

        $res = $rows;

        return response()->json($res, 200);
    }
}
