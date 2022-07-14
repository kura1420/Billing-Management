<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\RadiusApiRequest;
use App\Models\RadiusApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class RadiusApiController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = RadiusApi::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('host', 'like', "%$search%")
                ->orWhere('username', 'like', "%$search%")
                ->orWhere('password', 'like', "%$search%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(RadiusApiRequest $request)
    {
        RadiusApi::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'host' => $request->host,
                'active' => $request->active == "true" ? 1 : 0,
                'username' => $request->username,
                'password' => $request->password,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = RadiusApi::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        RadiusApi::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = RadiusApi::whereActive(1)->orderBy('name')->get();

        return response()->json($rows);
    }

    public function testConnection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'host' => 'required|string|max:100',
            'username' => 'required|string|max:100',
            'password' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'status' => 'NOT'
            ], 422);
        } else {
            $host = "$request->host/api/nas";
            $username = $request->username;
            $password = $request->password;

            $response = Http::withBasicAuth($username, $password)
                ->get($host);

            $response->throw();
        
            return $response->object();
        }        
    }
}
