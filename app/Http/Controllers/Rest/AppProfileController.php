<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppProfileRequest;
use App\Models\AppProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppProfileController extends Controller
{
    //
    protected $folder_image = 'app_profile';

    public function index()
    {
        $row = AppProfile::first();

        if ($row) {
            if ($row->logo) {
                $row->logo_url = asset('storage/' . $this->folder_image . '/' . $row->logo);
            }
        }

        return response()->json($row, 200);
    }

    public function store(AppProfileRequest $request)
    {
        $row = AppProfile::find($request->id);

        $logo = $row->logo ?? NULL;
        if ($request->logo) {
            $logo = Str::random(10) . '.' . $request->logo->extension();

            $request->file('logo')->storeAs(
                $this->folder_image,
                $logo,
                'public'
            );
        }

        AppProfile::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'shortname' => strtoupper($request->shortname),
                'telp' => $request->telp,
                'email' => strtolower($request->email),
                'fax' => $request->fax,
                'address' => $request->address,
                'logo' => $logo,
                'website' => $request->website,
            ],
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }
}
