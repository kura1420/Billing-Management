<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppController extends Controller
{
    //
    public function menu()
    {
        $res = [
            [
                'id' => rand(1, 999),
                'text' => 'Region',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Provinsi', 'url' => 'region/provinsi' ],
                    [ 'id' => rand(1, 999), 'text' => 'Kota', 'url' => 'region/city' ],
                    [ 
                        'id' => rand(1, 999), 
                        'text' => 'Area',
                        'children' => [
                            [ 'id' => rand(1, 999), 'text' => 'Data', 'url' => 'area' ],
                            [ 'id' => rand(1, 999), 'text' => 'Product' ],
                            [ 'id' => rand(1, 999), 'text' => 'Customer' ],
                        ], 
                    ],
                ],
            ],
            [
                'id' => rand(1, 999),
                'text' => 'Billing',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Tipe', 'url' => 'billing/type' ],
                    [ 'id' => rand(1, 999), 'text' => 'Template', 'url' => 'billing/template' ],
                    [ 'id' => rand(1, 999), 'text' => 'Invoice' ],
                ],
            ],
            [
                'id' => rand(1, 999),
                'text' => 'Customer',
                'children' => [
                    [ 'id' => rand(1, 999), 'text' => 'Tipe', 'url' => 'customer/type' ],
                    [ 'id' => rand(1, 999), 'text' => 'Segment', 'url' => 'customer/segment' ],
                    [ 'id' => rand(1, 999), 'text' => 'Data' ],
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
