<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductPromoRequest;
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
            ]
        );

        ProductPromoService::updateOrCreate(
            [
                'product_promo_id' => $productPromo->id,
            ],
            [
                'type' => $request->type,
                'discount' => $request->discount,
                'until_payment' => $request->until_payment,
                // 'product_promo_id' => $request->product_promo_id,
                'product_service_id' => $request->product_service_id,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = ProductPromo::find($id);
        $row->start = date('m/d/Y', strtotime($row->start));
        $row->end = date('m/d/Y', strtotime($row->end));
        
        $product_service = $row->product_promo_services()->first();

        $row->type = $product_service->type;
        $row->discount = $product_service->discount;
        $row->until_payment = $product_service->until_payment;
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
        $rows = ProductPromo::with('product_promo_services')->orderBy('name')->get();

        return response()->json($rows);
    }
}
