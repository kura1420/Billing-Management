<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\AppRoleMenu;
use App\Models\DepartementRole;
use Illuminate\Http\Request;

class AppController extends Controller
{
    //
    public function menu1()
    {
        $userProfile = session()->get('user_profile');
        
        $roleDepartement = DepartementRole::where('departement_id', $userProfile->departement_id)->first();

        $appRoleID = $roleDepartement->app_role_id;
        $roleDepartementMenu = AppRoleMenu::join('app_menus', 'app_role_menus.app_menu_id', '=', 'app_menus.id')
            ->where('app_role_menus.app_role_id', $appRoleID)
            ->whereNull('app_role_menus.parent')
            ->where('app_role_menus.active', 1)
            ->select([
                'app_role_menus.app_menu_id as id',
                    'app_menus.name as text',
                    'app_menus.title',
                    'app_menus.url',
            ])
            ->get()
            ->map(function($row) use ($appRoleID) {
                $children = [];
                $childs = AppRoleMenu::join('app_menus', 'app_role_menus.app_menu_id', '=', 'app_menus.id')
                    ->where('app_role_menus.app_role_id', $appRoleID)
                    ->where('app_role_menus.parent', $row->id)
                    ->where('app_role_menus.active', 1)
                    ->select([
                        'app_role_menus.app_menu_id as id',
                            'app_menus.name',
                            'app_menus.title',
                            'app_menus.url',
                    ])
                    ->get();
                foreach ($childs as $key => $value) {
                    $children[] = [
                        'id' => $value->id,
                        'text' => $value->name,
                        'title' => $value->title,
                        'url' => $value->url,
                    ];
                }

                $row->children = $children;

                return $row;
            });

        return response()->json($roleDepartementMenu, 200);
    }

    public function menu()
    {
        $res = [
            [
                'id' => rand(1, 999),
                'text' => 'Region',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Provinsi', 'url' => 'region/provinsi' ],
                    [ 'id' => rand(1, 999), 'text' => 'Kota', 'url' => 'region/city' ],
                    [ 'id' => rand(1, 999), 'text' => 'Area', 'url' => 'area' ],
                ],
            ],
            [
                'id' => rand(1, 999),
                'text' => 'Billing',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Tipe', 'url' => 'billing/type' ],
                    [ 'id' => rand(1, 999), 'text' => 'Template', 'url' => 'billing/template' ],
                    [ 'id' => rand(1, 999), 'text' => 'Invoice', 'url' => 'billing/invoice' ],
                ],
            ],
            [
                'id' => rand(1, 999),
                'text' => 'Customer',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Tipe', 'url' => 'customer/type' ],
                    [ 'id' => rand(1, 999), 'text' => 'Segment', 'url' => 'customer/segment' ],
                    [ 'id' => rand(1, 999), 'text' => 'Data', 'url' => 'customer' ],
                ],
            ],
            [
                'id' => rand(1, 999),
                'text' => 'Product',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Tipe', 'url' => 'product/type' ],
                    [ 'id' => rand(1, 999), 'text' => 'Service', 'url' => 'product/service' ],
                    [ 'id' => rand(1, 999), 'text' => 'Promo', 'url' => 'product/promo' ],
                ],
            ],
            [
                'id' => rand(1, 999),
                'text' => 'Setting',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Departement', 'url' => 'organization/departement' ],
                    [ 'id' => rand(1, 999), 'text' => 'Tax', 'url' => 'config/tax' ],
                ],
            ],
            [
                'id' => rand(1, 999),
                'text' => 'Core',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Profile', 'url' => 'core/profile' ],
                    [ 'id' => rand(1, 999), 'text' => 'User', 'url' => 'core/user' ],
                    [ 'id' => rand(1, 999), 'text' => 'Role', 'url' => 'core/role' ],
                    [ 'id' => rand(1, 999), 'text' => 'Menu', 'url' => 'core/menu' ],
                ],
            ],
        ];

        return response()->json($res);
    }
}
