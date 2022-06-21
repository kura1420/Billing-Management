<?php

namespace App\Http\Controllers\Rest;

use App\Helpers\Generated;
use App\Http\Controllers\Controller;
use App\Http\Requests\AppMenuRequest;
use App\Models\AppMenu;
use App\Models\AppRoleMenu;
use Illuminate\Http\Request;

class AppMenuController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = AppMenu::select('*');
        
        if ($search) {
            $result = $table->where('text', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")
                ->orderBy('text', 'asc')
                ->get();
        } else {
            $data = $table
                ->orderBy('text', 'asc')
                ->get();

            $result = Generated::buildTree($data);
        }
        
        return response()->json($result, 200);
    }

    public function store(AppMenuRequest $request)
    {
        $appMenu = AppMenu::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'text' => $request->text,
                'title' => $request->title,
                'url' => $request->url,
                'parent' => $request->parent ?? NULL,
                'active' => $request->active == 'true' ? 1 : 0,
            ]
        );

        if ($request->active !== 'true') {
            AppRoleMenu::where('app_menu_id', $appMenu->id)->update([
                'active' => 0
            ]);
        }

        $status = $request->id ? 200 : 201;

        return response()->json('OK', $status);
    }

    public function show($id)
    {
        $row = AppMenu::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        AppMenu::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {        
        $rows = AppMenu::where('active', 1)->orderBy('text', 'asc')->get();
        $result = Generated::buildTree($rows);

        return response()->json($result);
    }
}
