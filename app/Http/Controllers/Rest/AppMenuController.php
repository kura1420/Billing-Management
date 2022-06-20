<?php

namespace App\Http\Controllers\Rest;

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
        $search = $request->search ?? NULL;

        $table = AppMenu::with('children');
        
        if ($search) {
            $result = $table->where('text', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('url', 'like', "%{$search}%")
                ->get();
        } else {
            $result = $table->whereNull('parent')->get();
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
        // $rows = AppMenu::whereNull('parent')
        //     ->select('id', 'text', 'title', 'url')
        //     ->get()
        //     ->map(function($row) {
        //         $row->children = $row->childrenActive()->get();

        //         return $row;
        //     });

        $rows = AppMenu::select('id', 'text', 'title', 'url')
            ->get()
            ->map(function($row) {
                $row->children = $row->childrenActive()->get();

                return $row;
            });

        return response()->json($rows);
    }
}
