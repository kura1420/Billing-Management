<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppMenuRequest;
use App\Models\AppMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $table = AppMenu::select([
            'id',
            'name',
            'title',
            'url',
            'active',
            DB::raw('(select am.name from app_menus as am where am.id = app_menus.parent) AS parent')
        ]);

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(AppMenuRequest $request)
    {
        AppMenu::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'title' => $request->title,
                'url' => $request->url,
                'parent' => $request->parent ?? NULL,
                'active' => $request->active == 'true' ? 1 : 0,
            ]
        );

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
        $rows = AppMenu::where('active', 1)->whereNull('parent')->orderBy('name')->get();

        return response()->json($rows);
    }
}
