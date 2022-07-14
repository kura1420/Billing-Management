<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemController extends Controller
{
    //
    protected $folder_picture = 'item/picture';
    protected $folder_qrcode = 'item/qrcode';

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Item::join('partners', 'items.partner_id', '=', 'partners.id')
            ->select(
                'items.id',
                'items.code',
                'items.name',
                'items.serial_numbers',
                'items.brand',
                'partners.name as partner_id'
            );

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('items.code', 'like', "%{$search}%")
                ->orWhere('items.name', 'like', "%{$search}%")
                ->orWhere('items.serial_numbers', 'like', "%{$search}%")
                ->orWhere('items.brand', 'like', "%{$search}%")
                ->orWhere('partners.name', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(ItemRequest $request)
    {
        $row = Item::where('id', $request->id)->first();

        if (empty($row)) {
            $code = strtoupper($request->code);
            $qrcode = $code . '.svg';

            $picture = uniqid();

            $path = public_path('uploads/' . $this->folder_qrcode . '/' . $qrcode);

            QrCode::size(300)->generate($code, $path);
        } else {
            $code = $row->code;
            $qrcode = $row->qrcode;
            $picture = $row->picture;
        }
        
        if ($request->picture) {
            $picture = $picture . '.' . $request->picture->extension();

            $request->file('picture')->move(
                'uploads/' . $this->folder_picture,
                $picture,
            );
        }

        Item::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'code' => $code,
                'name' => $request->name,
                'serial_numbers' => $request->serial_numbers,
                'spec' => $request->spec,
                'desc' => $request->desc,
                'year' => $request->year,
                'picture' => $picture,
                'qrcode' => $qrcode,
                'price' => $request->price,
                'brand' => $request->brand,
                'partner_id' => $request->partner_id,
                'unit_id' => $request->unit_id,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = Item::find($id);
        $row->picture_url = url('uploads/' . $this->folder_picture. '/' . $row->picture);
        $row->qrcode_url = url('uploads/' . $this->folder_qrcode. '/' . $row->qrcode);

        return $row;
    }
    
    public function destroy($id)
    {
        Item::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Item::orderBy('name')->get();

        return response()->json($rows);
    }
}
