<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerDocumentRequest;
use App\Http\Requests\PartnerRequest;
use App\Models\Partner;
use App\Models\PartnerContact;
use App\Models\PartnerDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    //
    private $folder_image = 'partner';
    private $folder_file = 'partner_file';

    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = Partner::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('alias', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%")
                ->orWhere('telp', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('brand', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(PartnerRequest $request)
    {
        $contacts = json_decode($request->contacts, TRUE) ?? [];

        $row = Partner::find($request->id);

        $logo = $row->logo ?? NULL;
        if ($request->logo) {
            $logo = uniqid() . '.' . $request->logo->extension();

            $request->file('logo')->storeAs(
                $this->folder_image,
                $logo,
                'public',
            );
        }

        $partner = Partner::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'alias' => $request->alias,
                'type' => $request->type,
                'telp' => $request->telp,
                'email' => strtolower($request->email),
                'fax' => $request->fax,
                'handphone' => $request->handphone,
                'address' => $request->address,
                'logo' => $logo,
                'active' => $request->active == 'true' ? 1 : 0,
                'join' => $request->join ? date('Y-m-d', strtotime($request->join)) : NULL,
                'leave' => $request->leave ? date('Y-m-d', strtotime($request->leave)) : NULL,
                'brand' => $request->brand,
                'user_id_reff' => $request->user_id_reff,
            ]
        );

        if (!empty($contacts)) {
            foreach ($contacts as $contact) {
                $contactId = empty($contact['id']) ? NULL : $contact['id'];

                PartnerContact::updateOrCreate(
                    [
                        'id' => $contactId,
                    ],
                    [
                        'fullname' => $contact['fullname'],
                        'email' => $contact['email'],
                        'handphone' => $contact['handphone'],
                        'telp' => $contact['telp'],
                        'position' => $contact['position'],
                        'partner_id' => $partner->id,
                    ]
                );
            }
        }

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = Partner::find($id);
        
        if ($row->join) {
            $row->join = date('m/d/Y', strtotime($row->join));
        }

        if ($row->leave) {
            $row->leave = date('m/d/Y', strtotime($row->leave));
        }

        if ($row->logo) {
            $row->logo_url = asset('storage/' . $this->folder_image . '/' . $row->logo);            
        }

        return $row;
    }
    
    public function destroy($id)
    {
        Partner::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = Partner::orderBy('name')->get();

        return response()->json($rows);
    }

    public function contactLists($id)
    {
        $rows = PartnerContact::where('partner_id', $id)->get();

        return response()->json($rows);
    }

    public function contactDestroy($id)
    {
        PartnerContact::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function documentLists($id)
    {
        $rows = PartnerDocument::where('partner_id', $id)->get();

        return response()->json($rows);
    }

    public function documentStore(PartnerDocumentRequest $request)
    {
        $row = PartnerDocument::where('id', $request->d_id)->first();

        $file = $row->file ?? NULL;
        if ($request->d_file) {
            $file = uniqid() . '.' . $request->d_file->extension();

            $request->file('d_file')->storeAs(
                $this->folder_file,
                $file,
                'local'
            );
        }

        PartnerDocument::updateOrCreate(
            [
                'partner_id' => $request->partner_id,
            ],
            [
                'name' => $request->d_name,
                'file' => $file,
                'desc' => $request->d_desc,
                'start' => date('Y-m-d', strtotime($request->d_start)),
                'end' => date('Y-m-d', strtotime($request->d_end)),
            ]
        );

        $status = $request->partner_id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function documentShow($id)
    {
        $row = PartnerDocument::find($id);
        $row->file_url = url('/rest/partner/document-file/' . $row->id);

        return response()->json($row);
    }

    public function documentDestroy($id)
    {
        PartnerDocument::find($id)->delete();

        return response()->json('OK', 200);
    }
    
    public function documentFile($id)
    {
        $row = PartnerDocument::find($id);

        if ($row) {
            $path = Storage::path($this->folder_file . '/' . $row->file);

            return response()->file($path);
        } else {
            return abort(404);
        }        
    }
}
