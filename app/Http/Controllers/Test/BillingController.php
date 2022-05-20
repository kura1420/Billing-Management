<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    //
    public function index()
    {
        $now = Carbon::now();
        $addHour = $now->addHours(12);

        print_r($addHour);
    }
}
