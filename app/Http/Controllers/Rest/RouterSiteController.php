<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\RouterSiteRequest;
use App\Models\RouterSite;
use Illuminate\Http\Request;

class RouterSiteController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = RouterSite::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('site', 'like', "%{$search}%")
                ->orWhere('host', 'like', "%{$search}%")
                ->orWhere('port', 'like', "%{$search}%")
                ->orWhere('user', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(RouterSiteRequest $request)
    {
        RouterSite::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'site' => $request->site,
                'active' => $request->active == 'true' ? 1 : 0,
                'host' => $request->host,
                'port' => $request->port,
                'user' => $request->user,
                'password' => $request->password,
                'desc' => $request->desc,
            ]
        );

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = RouterSite::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        RouterSite::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = RouterSite::orderBy('site')->get();

        return response()->json($rows);
    }
}
