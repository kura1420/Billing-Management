<?php

namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerDataController extends Controller
{
    //
    public function lists()
    {
        $rows = Partner::orderBy('name')->get();
        
        return response()->json($rows);
    }

    public function types()
    {
        $check = Partner::whereNotNull('type')->count();

        $rows = [];
        if ($check>0) {
            $rows = Partner::distinct()
                ->select('type')
                ->get()
                ->map(function($row) {
                    return [
                        'id' => $row->type,
                        'text' => $row->type
                    ];
                });
        }

        return response()->json($rows);
    }

    public function brands()
    {
        $check = Partner::whereNotNull('brand')->count();
        
        $rows = [];
        if ($check>0) {
            $rows = Partner::distinct()
                ->select('brand')
                ->get()
                ->map(function($row) {
                    return [
                        'id' => $row->brand,
                        'text' => $row->brand
                    ];
                });
        }

        return response()->json($rows);
    }
}
