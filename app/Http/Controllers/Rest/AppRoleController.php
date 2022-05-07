<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppRoleRequest;
use App\Models\AppRole;
use App\Models\AppRoleMenu;
use App\Models\DepartementRole;
use Illuminate\Http\Request;

class AppRoleController extends Controller
{
    //
    public function index(Request $request)
    {
        $page = $request->page ?? 1;
        $rows = $request->rows ?? 10;
        $sortOrder = $request->sortOrder ?? 'asc';
        $sortName = $request->sortName ?? NULL;
        $search = $request->search ?? NULL;

        $table = AppRole::select('*');

        if ($sortName) {
            $result = $table->orderBy($sortName, $sortOrder)->paginate($rows);
        } elseif ($search) {
            $result = $table->where('name', 'like', "%{$search}%")
                ->paginate($rows);
        } else {
            $result = $table->paginate($rows);
        }
        
        return response()->json($result, 200);
    }

    public function store(AppRoleRequest $request)
    {
        $departements = json_decode($request->departements, TRUE);
        $menus = json_decode($request->menus, TRUE);

        $appRole = AppRole::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'name' => $request->name,
                'desc' => $request->desc,
                'active' => $request->active == 'true' ? 1 : 0,
            ]
        );

        if (!empty($departements)) {
            foreach ($departements as $key => $departement) {
                DepartementRole::updateOrCreate(
                    [
                        'app_role_id' => $appRole->id,
                        'departement_id' => $departement['departement_id']
                    ],
                    [
                        'active' => $departement['active'] == 'Yes' ? 1 : 0,
                    ]
                );
            }
        }

        if (!empty($menus)) {
            foreach ($menus as $key => $menu) {
                AppRoleMenu::updateOrCreate(
                    [
                        'app_role_id' => $appRole->id,
                        'app_menu_id' => $menu['id']
                    ],
                    [
                        'active' => $menu['active'],
                        'parent' => $menu['parent']
                    ]
                );
            }
        }

        $status = $request->id ? 200 : 201;

        return response()->json($appRole, $status);
    }

    public function show($id)
    {
        $row = AppRole::find($id);

        return $row;
    }
    
    public function destroy($id)
    {
        AppRole::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function lists()
    {
        $rows = AppRole::where('active', 1)->orderBy('name')->get();

        return response()->json($rows);
    }

    public function departementLists($id)
    {
        $rows = DepartementRole::where('app_role_id', $id)
            ->get()
            ->map(function ($row) {
                return [
                    'id' => $row->id,
                    'departement_id' => $row->departement_id,
                    'name' => \App\Models\Departement::where('id', $row->departement_id)->first()->name,
                    'active' => $row->active == 1 ? 'Yes' : 'No',
                ];
            });

        return response()->json($rows);
    }

    public function departementDestroy($id)
    {
        DepartementRole::find($id)->delete();

        return response()->json('OK', 200);
    }

    public function menuLists($id)
    {
        $rows = AppRoleMenu::where('app_role_id', $id)
            ->whereNull('parent')
            ->select('id', 'app_menu_id', 'parent', 'active')
            ->get()
            ->map(function($row) {
                $row->id = $row->app_menu_id;
                $row->name = \App\Models\AppMenu::where('id', $row->app_menu_id)->first()->name;
                $row->parent = $row->parent;
                $row->active = $row->active == 1 ? 'Yes' : 'No';

                $children = [];
                foreach ($row->children()->get() as $key => $value) {
                    $children[] = [
                        'id' => $value->app_menu_id,
                        'name' => \App\Models\AppMenu::where('id', $value->app_menu_id)->first()->name,
                        'parent' => $value->parent,
                        'active' => $value->active == 1 ? 'Yes' : 'No',
                    ];
                }

                $row->children = $children;

                return $row;
            });
        
        return response()->json($rows, 200);
    }
}
